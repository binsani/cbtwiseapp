import { app, BrowserWindow, Menu, shell } from 'electron';
import path from 'path';
import { fileURLToPath } from 'url';
import { spawn } from 'child_process';
import net from 'net';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

let phpProcess = null;
const PHP_PORT = 8000;
const SERVER_URL = `http://127.0.0.1:${PHP_PORT}`;

// Check if port is open
function waitForServer(port, callback) {
  const client = new net.Socket();
  const check = () => {
    client.connect({ port, host: '127.0.0.1' }, () => {
      client.end();
      callback();
    });
  };
  client.on('error', () => {
    setTimeout(check, 300);
  });
  check();
}

function startPhpServer() {
  const isPackaged = app.isPackaged;
  const basePath = isPackaged
    ? path.join(process.resourcesPath, 'app')
    : __dirname;

  const phpExecutable = isPackaged
    ? path.join(process.resourcesPath, 'app', 'php-win', 'php.exe')
    : path.join(__dirname, 'php-win', 'php.exe');

  const publicPath = path.join(basePath, 'public');

  // Spawn local PHP development server
  phpProcess = spawn(
    phpExecutable,
    ['-S', `127.0.0.1:${PHP_PORT}`, '-t', publicPath],
    {
      cwd: basePath,
      windowsHide: true,
    }
  );

  phpProcess.on('error', (err) => {
    console.error('Failed to start internal PHP server:', err);
  });
}

function createWindow() {
  const mainWindow = new BrowserWindow({
    width: 1280,
    height: 800,
    minWidth: 1024,
    minHeight: 700,
    title: 'CBTwise — AI-Powered CBT Prep Platform (Offline Edition)',
    icon: path.join(__dirname, 'public', 'icons', 'icon-512x512.png'),
    webPreferences: {
      nodeIntegration: false,
      contextIsolation: true,
      sandbox: true,
    },
    autoHideMenuBar: false,
  });

  // Load local standalone PHP server once ready
  waitForServer(PHP_PORT, () => {
    mainWindow.loadURL(SERVER_URL);
  });

  // Handle external links opening in user's default browser
  mainWindow.webContents.setWindowOpenHandler(({ url }) => {
    if (url.startsWith('http:') || url.startsWith('https:')) {
      if (!url.includes('127.0.0.1') && !url.includes('localhost')) {
        shell.openExternal(url);
        return { action: 'deny' };
      }
    }
    return { action: 'allow' };
  });

  // Custom application menu
  const menuTemplate = [
    {
      label: 'Navigation',
      submenu: [
        {
          label: 'Dashboard',
          click: () => mainWindow.loadURL(`${SERVER_URL}/dashboard`),
        },
        {
          label: 'Start Practice Exam',
          click: () => mainWindow.loadURL(`${SERVER_URL}/exam/setup`),
        },
        { type: 'separator' },
        { label: 'Reload', role: 'reload' },
        { label: 'Force Reload', role: 'forceReload' },
        { type: 'separator' },
        { label: 'Quit CBTwise', role: 'quit' },
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
  ];

  const menu = Menu.buildFromTemplate(menuTemplate);
  Menu.setApplicationMenu(menu);
}

app.whenReady().then(() => {
  startPhpServer();
  createWindow();

  app.on('activate', () => {
    if (BrowserWindow.getAllWindows().length === 0) createWindow();
  });
});

app.on('window-all-closed', () => {
  if (phpProcess) phpProcess.kill();
  if (process.platform !== 'darwin') app.quit();
});

app.on('will-quit', () => {
  if (phpProcess) phpProcess.kill();
});
