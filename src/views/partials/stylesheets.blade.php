@include('mail-mango::partials/normalize')

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/semantic.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/components/icon.min.css">
<link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">


<style>
    html {
        box-sizing: border-box;
    }
    *, *:before, *:after {
        box-sizing: inherit;
    }
    body {
        font-size: 14px;
        font-family: 'Lato', sans-serif;
        font-weight: 400;
    }
    #app {
        display: flex;
        min-height: 100vh;
        font-size: 14px;
    }
    .sidebar {
        flex: 0 0 385px;
        border-right: 1px solid darkgray;
        height: 100vh;
        overflow-x: hidden;
    }
    .sidebar ul {
        padding: 0;
        margin: 0;
        padding-top: 12px;
    }
    .sidebar ul li {
        list-style: none;
        padding: 7px 13px;
        background: whitesmoke;
        border-bottom: 3px solid white;
        cursor: pointer;
        color: #0d0d0d;
        transition: background 0.1s ease, color 0.1s ease;
    }
    .sidebar ul li:hover, .sidebar ul li.active {
        background: #334286;
        color: white;
    }

    .sidebar ul li .date {
        font-size: 12px;
        opacity: 0.7;
    }

    .content {
        flex: 1;
        background: white;
    }

    .content {
        padding: 20px 10px 0;
    }

    .heading {
        display: flex;
        justify-content: space-between;
    }

    .options {
        text-align: right;
        padding: 12px 4px 4px;
    }
    .ui.primary.button, .ui.primary.buttons .button {
        background: #37709e;
    }
    .ui.primary.button:hover, .ui.primary.buttons .button:hover, .ui.primary.button:focus, .ui.primary.buttons .button:focus {
        background: #334286;
    }

    .content .not-found {
        text-align: center;
        font-size: 20px;
        display: flex;
        height: 100vh;
        justify-content: center;
        color: #0d0d0d;
        padding-top: 25px;
    }

    .mail-data {
        padding-bottom: 20px;
    }

    iframe {
        width: 100%;
        height: calc(100vh - 200px);
    }

    [v-cloak] {
        display: none;
    }
</style>