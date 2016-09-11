@include('mail-mango::partials/normalize')

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.1.0/milligram.min.css">
<link rel="stylesheet" type="text/css"
      href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    body {
        background: white;
        overflow: hidden;
    }

    button {
        font-size: 15px !important;
    }

    button.danger {
        border-color: #ff4828 !important;
        color: #ff4828 !important;
    }

    #app {
        display: flex;
        min-height: 100vh;
    }

    .sidebar {
        flex: 0 0 250px;
        border-right: 1px solid darkgray;
        height: 100vh;
        overflow-y: scroll;
        overflow-x: hidden;
    }

    .sidebar .options {
        margin: 5px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .sidebar ul {
        list-style: none;
    }

    .sidebar ul li {
        cursor: pointer;
        padding: 10px 15px;
        margin: 0;
        border-bottom: 1px solid #b1b1a0;
    }

    .sidebar ul li .date {
        font-size: 11px;
        display: block;
    }

    .sidebar ul li .subject {
        display: block;
        font-size: 14px;
    }

    .sidebar ul li:hover, .sidebar ul li.active {
        background: whitesmoke;
    }

    .content {
        flex: 1;
        background: white;
    }

    .content .not-found {
        text-align: center;
        font-size: 20px;
        display: flex;
        align-items: center;
        height: 100vh;
        justify-content: center;
    }

    .mail-data {
        height: 100px;
        text-align: left;
        font-size: 12px;
        margin-left: 20px;
        margin-right: 20px;
    }

    .mail-data .heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .mail-data .heading .fa {
        font-size: 20px;
    }

    .mail-data h4, .mail-data p {
        margin-bottom: 10px;
    }

    .text-content-wrap {
        margin: 20px;
    }
    .text-content-wrap pre {
        padding: 5px;
    }

    iframe {
        width: calc(100% - 40px);
        margin: 20px;
        border: 1px solid #b1b1a0;
        height: calc(100vh - 200px);
    }

    [v-cloak] {
        display: none;
    }
</style>