<?php

return [
    /**
     * Custom console command for opening browser. MUST include URL placeholder.
     * Defaults to "xdg open" on Linux and "open" on Mac
     * If you are on Windows you will NEED to configure this
     * Example: "google-chrome URL --incognito > /dev/null 2>&1 &"
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
     * Whether to disable email opening after it is sent.
     * All emails are still visible at site/mail-mango
     */

    'disable_automatic_opening' => false,

    /**
     *
     * Whether to disable email opening after it is sent from background process.
     * This uses Laravel's  App::runningInConsole()
     * All emails are still visible at site/mail-mango
     */

    'disable_automatic_opening_from_background' => false
];