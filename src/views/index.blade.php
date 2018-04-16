@extends('mail-mango::layout')

@section('content')
    <div v-cloak id="app" xmlns:v-bind="http://www.w3.org/1999/xhtml">
        <div v-if="mails.length > 0" class="sidebar">
            <div class="options">
                <button @click="reloadList()" class="ui icon button">
                    <i class="sync icon"></i>
                </button>

                <button v-if="mails.length !== 0" @click="deleteAll()" class="ui icon secondary button">
                    <i class="trash icon"></i>
                </button>
            </div>
            <ul>
                <li v-for="mail in mails" v-bind:class="{ active: isActive(mail) }" @click="load(mail)">
                    <div class="subject">@{{mail.subject}}</div>
                    <div class="date">@{{mail.nice_date}}</div>
                </li>
            </ul>
        </div>

        <div v-cloak v-if="mail" class="content">
            <div class="mail-data">
                <div class="heading">
                    <h2 class="ui header">@{{mail.subject}}</h2>
                    <button v-if="mails.length !== 0" @click.prevent="deleteCurrent()" class="ui secondary icon button">
                        <i class="trash icon"></i>
                    </button>
                </div>
                <div class="details">
                    <div>
                        <strong>From:</strong>
                        <span class="values">
                        @{{personList(mail.from)}}
                    </span>
                    </div>
                    <div>
                        <strong>To:</strong>
                        <span class="values">
                        @{{personList(mail.to)}}
                    </span>
                    </div>
                </div>
            </div>

            <div class="ui top attached tabular menu">
                <div class="item" @click="tab = 'html'" v-bind:class="{ active: tab === 'html' }">Html</div>
                <div class="item" @click="tab = 'text'" v-bind:class="{ active: tab === 'text' }">Text</div>
                <div class="item">
                    <a :href="emlUrl" target="_blank">Eml</a>
                </div>
            </div>
            <div v-if="tab === 'html'" class="ui bottom attached active tab segment">
                <iframe v-bind:src="iframeContent" frameborder="0">
                </iframe>
            </div>
            <div v-if="tab === 'text'" class="ui bottom attached active tab segment">
                <pre>@{{ textContent }}</pre>
            </div>


        </div>
        <div v-else class="content">
            <div class="not-found">
                <div v-if="mails.length === 0" class="message">
                    No emails currently available
                    <br>
                    <br>
                    <button @click="reloadList()" class="ui icon primary button">
                        <i class="sync icon"></i>
                    </button>
                </div>
                <div v-else="" class="message">
                    Select email on the left to view
                </div>
            </div>
        </div>
    </div>
@endsection