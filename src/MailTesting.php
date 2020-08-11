<?php

namespace Gwdhost\MailTesting;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class MailTesting extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('mail-testing::navigation');
    }
}
