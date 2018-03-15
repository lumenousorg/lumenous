<?php

namespace lumenous\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use lumenous\Mail\ContactUs;
use lumenous\Http\Requests\Pages\ContactUsRequest;

class PagesController extends Controller {

    /**
     * Displays home page.
     * 
     * @return \Illuminate\View\View
     */
    public function getHome()
    {
        return view('pages.home');
    }

    public function setInflation()
    {
        return view('pages.set-inflation');
    }

    /**
     * Displays contact us page.
     * 
     * @return \Illuminate\View\View
     */
    public function getContact()
    {
        return view('pages.contact-us');
    }

    /**
     * Display About page.
     *
     * @return \Illuminate\View\View
     */
    public function getFaq()
    {
        return view('pages.faq');
    }

    /**
     * Display About page.
     *
     * @return \Illuminate\View\View
     */
    public function getAbout()
    {
        return view('pages.about');
    }

    /**
     * Display Privacy page.
     *
     * @return \Illuminate\View\View
     */
    public function getPrivacy()
    {
        return view('pages.privacy');
    }

    /**
     * Display Terms of Service page.
     *
     * @return \Illuminate\View\View
     */
    public function getTerms()
    {
        return view('pages.tos');
    }

    /**
     * Submit contact us form.
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function postContact(ContactUsRequest $request)
    {
        $name = $request->get('name');
        $email = $request->get('email');
        $subject = $request->get('subject');
        $message = $request->get('message');

        $to = config('lumenous.contact-us-email');
        Mail::to($to)->send(new ContactUs(compact('name', 'email', 'subject', 'message')));

        return view('pages.contact-us');
    }

}
