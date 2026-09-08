import { app, BrowserWindow, Menu, shell, dialog } from 'electron';
import path from 'path';
import { fileURLToPath } from 'url';
import { spawn } from 'child_process';
import net from 'net';
import fs from 'fs';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

let mainWindow = null;
let phpProcess = null;
const PHP_PORT = 8000;
const OFFLINE_URL = `http://127.0.0.1:${PHP_PORT}`;
const ONLINE_URL = 'https://cbtwise.com.ng';

// Settings file path for remembering user choice
function getConfigPath() {
  return path.join(app.getPath('userData'), 'cbtwise-config.json');
}

function getSavedMode() {
  try {
    const configPath = getConfigPath();
    if (fs.existsSync(configPath)) {
      const data = JSON.parse(fs.readFileSync(configPath, 'utf8'));
      if (data.mode === 'offline' || data.mode === 'online') {
        return data.mode;
      }
    }
  } catch (e) {
    // Ignore and fallback
  }
  // Default to online if not specified
  return 'online';
}

function saveMode(mode) {
  try {
    const configPath = getConfigPath();
    fs.writeFileSync(configPath, JSON.stringify({ mode }, null, 2), 'utf8');
  } catch (e) {
    console.error('Failed to save config:', e);
  }
}

let currentMode = getSavedMode();

// Check if a TCP port is open
function waitForServer(port, timeoutMs = 15000) {
  return new Promise((resolve, reject) => {
    const start = Date.now();
    const check = () => {
      const client = new net.Socket();
      client.connect({ port, host: '127.0.0.1' }, () => {
        client.end();
        resolve(true);
      });
      client.on('error', () => {
        client.destroy();
        if (Date.now() - start > timeoutMs) {
          reject(new Error(`Timeout waiting for internal PHP server on port ${port}`));
        } else {
          setTimeout(check, 300);
        }
      });
    };
    check();
  });
}

// Locate appropriate PHP binary across Windows, macOS, and Linux
function resolvePhpExecutable() {
  const isPackaged = app.isPackaged;
  const platform = process.platform;
  const basePath = isPackaged
    ? path.join(process.resourcesPath, 'app')
    : __dirname;

  if (platform === 'win32') {
    const winPath = isPackaged
      ? path.join(process.resourcesPath, 'app', 'php-win', 'php.exe')
      : path.join(__dirname, 'php-win', 'php.exe');
    return fs.existsSync(winPath) ? winPath : 'php';
  }

  if (platform === 'darwin') {
    const macBundled = isPackaged
      ? path.join(process.resourcesPath, 'app', 'php-mac', 'php')
      : path.join(__dirname, 'php-mac', 'php');
    if (fs.existsSync(macBundled)) return macBundled;

    // Common macOS Homebrew and system locations
    const macPaths = [
      '/opt/homebrew/bin/php',
      '/usr/local/bin/php',
      '/usr/bin/php',
    ];
    for (const p of macPaths) {
      if (fs.existsSync(p)) return p;
    }
    return 'php';
  }

  if (platform === 'linux') {
    const linuxBundled = isPackaged
      ? path.join(process.resourcesPath, 'app', 'php-linux', 'php')
      : path.join(__dirname, 'php-linux', 'php');
    if (fs.existsSync(linuxBundled)) return linuxBundled;

    const linuxPaths = [
      '/usr/bin/php',
      '/usr/local/bin/php',
    ];
    for (const p of linuxPaths) {
      if (fs.existsSync(p)) return p;
    }
    return 'php';
  }

  return 'php';
}

function stopPhpServer() {
  if (phpProcess) {
    try {
      phpProcess.kill();
    } catch (e) {
      // Ignore
    }
    phpProcess = null;
  }
}

async function startPhpServer() {
  if (phpProcess) return true;

  const isPackaged = app.isPackaged;
  const basePath = isPackaged
    ? path.join(process.resourcesPath, 'app')
    : __dirname;

  const publicPath = path.join(basePath, 'public');
  const phpExecutable = resolvePhpExecutable();

  try {
    phpProcess = spawn(
      phpExecutable,
      ['-S', `127.0.0.1:${PHP_PORT}`, '-t', publicPath],
      {
        cwd: basePath,
        windowsHide: true,
      }
    );

    phpProcess.on('error', (err) => {
      console.error('Failed to spawn PHP process:', err);
      if (mainWindow) {
        dialog.showMessageBox(mainWindow, {
          type: 'error',
          title: 'Offline Engine Notice',
          message: `Unable to start local PHP engine (${err.message}).\n\nWould you like to switch to Online Mode to use CBTwise directly over the internet?`,
          buttons: ['Switch to Online Mode', 'Cancel'],
          defaultId: 0,
        }).then(({ response }) => {
          if (response === 0) {
            setMode('online');
          }
        });
      }
      phpProcess = null;
    });

    await waitForServer(PHP_PORT);
    return true;
  } catch (err) {
    console.error('PHP server error:', err);
    return false;
  }
}

function getBaseUrl() {
  return currentMode === 'offline' ? OFFLINE_URL : ONLINE_URL;
}

async function setMode(newMode) {
  currentMode = newMode;
  saveMode(newMode);
  updateApplicationMenu();

  if (!mainWindow) return;

  if (newMode === 'offline') {
    mainWindow.setTitle('CBTwise — AI-Powered CBT Platform (Offline Standalone Engine)');
    const ok = await startPhpServer();
    if (ok) {
      mainWindow.loadURL(`${OFFLINE_URL}/dashboard`);
    }
  } else {
    mainWindow.setTitle('CBTwise — AI-Powered CBT Platform (Online Cloud Edition)');
    stopPhpServer();
    mainWindow.loadURL(ONLINE_URL);
  }
}

function buildMenuTemplate() {
  return [
    {
      label: 'Mode',
      submenu: [
        {
          label: '🌐 Online Mode (cbtwise.com.ng)',
          type: 'radio',
          checked: currentMode === 'online',
          click: () => setMode('online'),
        },
        {
          label: '⚡ Offline Mode (Bundled Local Engine)',
          type: 'radio',
          checked: currentMode === 'offline',
          click: () => setMode('offline'),
        },
        { type: 'separator' },
        {
          label: 'About Modes...',
          click: () => {
            dialog.showMessageBox(mainWindow, {
              type: 'info',
              title: 'CBTwise Modes',
              message: 'CBTwise Dual-Mode Architecture',
              detail: '• Online Mode (Cloud): Connects directly to cbtwise.com.ng for live questions, national leaderboards, and instant AI tutor updates.\n\n• Offline Mode (Local Engine): Runs completely offline using the built-in standalone PHP engine so you can practice anytime without internet.',
              buttons: ['OK'],
            });
          },
        },
      ],
    },
    {
      label: 'Navigation',
      submenu: [
        {
          label: 'Dashboard',
          accelerator: 'CmdOrCtrl+D',
          click: () => mainWindow && mainWindow.loadURL(`${getBaseUrl()}/dashboard`),
        },
        {
          label: 'Start Practice Exam',
          accelerator: 'CmdOrCtrl+N',
          click: () => mainWindow && mainWindow.loadURL(`${getBaseUrl()}/exam/setup`),
        },
        {
          label: 'JAMB Simulator',
          click: () => mainWindow && mainWindow.loadURL(`${getBaseUrl()}/exam/setup?exam=jamb`),
        },
        { type: 'separator' },
        { label: 'Reload Page', role: 'reload', accelerator: 'CmdOrCtrl+R' },
        { label: 'Force Reload', role: 'forceReload', accelerator: 'CmdOrCtrl+Shift+R' },
        { type: 'separator' },
        { label: 'Quit CBTwise', role: 'quit', accelerator: 'CmdOrCtrl+Q' },
      ],
    },
    {
      label: 'View',
      submenu: [
        { label: 'Reset Zoom', role: 'resetZoom' },
        { label: 'Zoom In', role: 'zoomIn' },
        { label: 'Zoom Out', role: 'zoomOut' },
        { type: 'separator' },
        { label: 'Toggle Full Screen', role: 'togglefullscreen' },
      ],
    },
    {
      label: 'Help',
      submenu: [
        {
          label: 'Visit Official Website',
          click: () => shell.openExternal('https://cbtwise.com.ng'),
        },
        {
          label: 'Support & FAQs',
          click: () => shell.openExternal('https://cbtwise.com.ng/faq'),
        },
      ],
    },
  ];
}

function updateApplicationMenu() {
  const menu = Menu.buildFromTemplate(buildMenuTemplate());
  Menu.setApplicationMenu(menu);
}

// Fallback HTML when internet connection is down in online mode
function showNetworkErrorPage(failedUrl) {
  if (!mainWindow) return;
  const html = `
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <title>Connection Required — CBTwise</title>
      <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        body { background: #0f172a; color: #f8fafc; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .card { background: #1e293b; border: 1px solid #334155; border-radius: 24px; padding: 40px; max-width: 520px; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.4); }
        .icon { width: 64px; height: 64px; margin: 0 auto 20px; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 28px; }
        h1 { font-size: 22px; font-weight: 800; margin-bottom: 12px; color: #fff; }
        p { font-size: 14px; color: #94a3b8; line-height: 1.6; margin-bottom: 24px; }
        .buttons { display: flex; flex-direction: column; gap: 12px; }
        button { border: none; outline: none; cursor: pointer; padding: 14px 20px; border-radius: 14px; font-size: 14px; font-weight: 700; transition: all 0.2s; }
        .btn-offline { background: linear-gradient(135deg, #059669, #0d9488); color: white; box-shadow: 0 4px 14px rgba(5, 150, 105, 0.4); }
        .btn-offline:hover { opacity: 0.95; transform: translateY(-1px); }
        .btn-retry { background: #334155; color: #e2e8f0; }
        .btn-retry:hover { background: #475569; }
      </style>
    </head>
    <body>
      <div class="card">
        <div class="icon">⚡</div>
        <h1>Unable to Connect to CBTwise Cloud</h1>
        <p>You appear to be offline or experiencing connection issues with <strong>cbtwise.com.ng</strong>.<br><br>You can switch immediately to <strong>Offline Mode</strong> to practice with local exam banks without internet.</p>
        <div class="buttons">
          <button class="btn-offline" onclick="window.electronAPI ? window.electronAPI.switchOffline() : location.href='cbtwise://switch-offline'">⚡ Switch to Offline Mode</button>
          <button class="btn-retry" onclick="location.reload()">🔄 Retry Cloud Connection</button>
        </div>
      </div>
    </body>
    </html>
  `;
  mainWindow.loadURL(\`data:text/html;charset=utf-8,\${encodeURIComponent(html)}\`);
}

function createWindow() {
  mainWindow = new BrowserWindow({
    width: 1280,
    height: 800,
    minWidth: 1024,
    minHeight: 700,
    title: \`CBTwise — AI-Powered CBT Platform (\${currentMode === 'offline' ? 'Offline Standalone' : 'Online Cloud'})\`,
    icon: path.join(__dirname, 'public', 'icons', 'icon-512x512.png'),
    webPreferences: {
      nodeIntegration: false,
      contextIsolation: true,
      sandbox: true,
    },
    autoHideMenuBar: false,
  });

  updateApplicationMenu();

  // Listen for navigation errors in Online Mode
  mainWindow.webContents.on('did-fail-load', (event, errorCode, errorDescription, validatedURL) => {
    if (currentMode === 'online' && !validatedURL.startsWith('data:')) {
      console.warn(\`Failed to load \${validatedURL}: [\${errorCode}] \${errorDescription}\`);
      showNetworkErrorPage(validatedURL);
    }
  });

  // Catch custom offline switch requests from the error page
  mainWindow.webContents.on('will-navigate', (event, url) => {
    if (url.startsWith('cbtwise://switch-offline')) {
      event.preventDefault();
      setMode('offline');
    }
  });

  // Handle external links opening in default system browser
  mainWindow.webContents.setWindowOpenHandler(({ url }) => {
    if (url.startsWith('http:') || url.startsWith('https:')) {
      if (
        !url.includes('127.0.0.1') &&
        !url.includes('localhost') &&
        !url.includes('cbtwise.com.ng')
      ) {
        shell.openExternal(url);
        return { action: 'deny' };
      }
    }
    return { action: 'allow' };
  });

  // Initial boot based on saved mode
  if (currentMode === 'offline') {
    startPhpServer().then((ok) => {
      if (ok) {
        mainWindow.loadURL(\`\${OFFLINE_URL}/dashboard\`);
      } else {
        setMode('online');
      }
    });
  } else {
    mainWindow.loadURL(ONLINE_URL);
  }
}

app.whenReady().then(() => {
  createWindow();

  app.on('activate', () => {
    if (BrowserWindow.getAllWindows().length === 0) createWindow();
  });
});

app.on('window-all-closed', () => {
  stopPhpServer();
  if (process.platform !== 'darwin') app.quit();
});

app.on('will-quit', () => {
  stopPhpServer();
});
