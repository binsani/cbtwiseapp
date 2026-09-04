<?php

namespace App\Http\Controllers;

use App\Models\FaqItem;
use App\Models\ContactMessage;
use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function faq()
    {
        $faqs = FaqItem::active()->get();
        return view('pages.faq', compact('faqs'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:2000',
            'honeypot' => 'size:0', // spam trap
        ]);

        ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
        ]);

        return back()->with('success', 'Thank you! We will get back to you within 24 hours.');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function refundPolicy()
    {
        return view('pages.refund-policy');
    }

    public function robots()
    {
        $content = "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /dashboard\nDisallow: /account\nSitemap: " . url('/sitemap.xml');
        return response($content, 200, ['Content-Type' => 'text/plain']);
    }

    public function sitemap()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/')->setPriority(1.0)->setChangeFrequency('weekly'))
            ->add(Url::create('/about')->setPriority(0.7))
            ->add(Url::create('/exams')->setPriority(0.8))
            ->add(Url::create('/subjects')->setPriority(0.8))
            ->add(Url::create('/blog')->setPriority(0.7))
            ->add(Url::create('/faq')->setPriority(0.6))
            ->add(Url::create('/contact')->setPriority(0.5))
            ->add(Url::create('/pricing')->setPriority(0.9));

        foreach (Exam::active()->get() as $exam) {
            $sitemap->add(Url::create('/exams/' . $exam->slug)->setPriority(0.8));
        }

        foreach (Subject::active()->get() as $subject) {
            $sitemap->add(Url::create('/subjects/' . $subject->slug)->setPriority(0.7));
        }

        foreach (\App\Models\BlogPost::published()->get() as $post) {
            $sitemap->add(Url::create('/blog/' . $post->slug)->setPriority(0.6));
        }

        foreach (\App\Models\SeoPage::published()->with(['exam', 'subject'])->get() as $page) {
            if ($page->exam && $page->subject) {
                $sitemap->add(Url::create('/' . $page->exam->slug . '/' . $page->subject->slug . '/' . $page->year)->setPriority(0.6));
            }
        }

        return $sitemap->toResponse(request());
    }
}
