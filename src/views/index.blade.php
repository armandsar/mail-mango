@extends('mail-mango::layout')

@section('content')
    <div v-cloak id="app" xmlns:v-bind="http://www.w3.org/1999/xhtml">
        <div class="sidebar">
            <div class="options">
                <button @click="reloadList()" class="button button-outline">
                <i class="fa fa-refresh"></i>
                </button>
                <button v-if="mails.length !== 0" @click="deleteAll()" class="button button-outline danger">
                <i class="fa fa-trash"></i>
                </button>
            </div>
            <ul>
                <li v-for="mail in mails" v-bind:class="{ active: isActive(mail) }" @click="load(mail.file)">
                <span class="subject">@{{mail.subject}}</span>
                <span class="date">@{{mail.date}}</span>
                </li>
            </ul>
        </div>

        <div v-cloak v-if="mail" class="content">
            <div class="mail-data">
                <div class="heading">
                    <h4>@{{mail.subject}}</h4>
                    <a @click.prevent="deleteCurrent()" href="#">
                        <i class="fa fa-trash"></i>
                    </a>
                </div>
                <p>
                    <strong>From</strong>
                    <span class="values">
                        @{{ Object.keys(mail.from).join(', ') }}
                    </span>
                    <strong>to</strong>
                    <span class="values">
                        @{{ Object.keys(mail.to).join(', ') }}
                    </span>
                </p>
            </div>

            <div class="text-content-wrap" v-if="text_content">
                <a @click.prevent="textVersionShown = !textVersionShown" href="#">Show text version</a>
                <a target="_blank" :href="emlPath()">Download eml</a>

                <pre v-if="textVersionShown">@{{ text_content }}</pre>
            </div>

            <iframe v-bind:src="iframe_content" frameborder="0">

            </iframe>
        </div>
        <div v-else class="content">
            <div class="not-found">
                <div v-if="mails.length === 0" class="message">
                    No emails currently available <br>
                    <button @click="reloadList()" class="button button-outline">
                    <i class="fa fa-refresh"></i>
                    </button>
                </div>
                <div v-else="" class="message">
                    Select email on the left to view
                </div>
            </div>
        </div>
    </div>
@endsection