<?php

return [
    /**
     * Custom console command for opening browser.
     * MUST include $URL placeholder.
     * Defaults to "xdg open" on Linux and "open" on Mac
     * If you are on Windows you will NEED to configure this
     * Example: 'google-chrome $URL --incognito > /dev/null 2>&1 &'
     */
    'command' => null,

    /**
     * Email lifetime in seconds
     * After this period all old emails will be deleted
     * Default: 1 day
     */

    'email_lifetime' => 86400,

    /**
     *
     * Whether email view is opened after sending email
     * All emails are still visible at http://awesome-site.dev/mail-mango
     */

    'automatic_opening' => true,

    /**
     *
     * Whether email view is opened after sending email from background process.
     * This uses Laravel's  App::runningInConsole()
     * All emails are still visible at http://awesome-site.dev/mail-mango
     */

    'automatic_opening_from_background' => true
];