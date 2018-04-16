<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.0.1/vue.js"></script>
<script src="https://cdn.jsdelivr.net/vue.resource/1.0.3/vue-resource.min.js"></script>

<script>

    function rawurlencode(str) {
        return encodeURIComponent(str)
            .replace(/!/g, '%21')
            .replace(/'/g, '%27')
            .replace(/\(/g, '%28')
            .replace(/\)/g, '%29')
            .replace(/\*/g, '%2A')
    }

    function getParameterByName(name) {
        var url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    var app = new Vue({
        http: {
            root: '/mail-mango'
        },
        el: '#app',
        data: {
            mail: null,
            current: null,
            mails: [],
            tab: 'html'
        },
        computed: {
            'iframeContent': function () {
                return "data:text/html;charset=utf-8," + rawurlencode(this.mail.parts[0].content);
            },
            'textContent': function () {
                if (!this.mail.parts[1]) {
                    return null;
                }
                return this.mail.parts[1].content;
            },
            'emlUrl': function () {
                return '/mail-mango/' + this.current + '/eml';
            }
        },
        methods: {
            load: function (mail) {
                this.current = mail.code;
                this.$http.get(mail.code).then(function (response) {
                    this.mail = response.data;
                    this.tab = this.firstTab()
                });
            },
            firstTab: function () {
                return 'html'
            },
            personList: function (list) {
                var str = [];
                for (var email in list) {
                    if (list.hasOwnProperty(email)) {
                        var name = list[email];
                        if (name) {
                            str.push(name + " (" + email + ")")
                        } else {
                            str.push(email)
                        }
                    }
                }
                return str.join(', ')
            },
            isActive: function (mail) {
                return this.current === mail.code;
            },
            deleteAll: function () {
                this.$http.delete('all').then(function (response) {
                    this.reloadList();
                });
            },
            deleteCurrent: function () {
                this.$http.delete(this.current).then(function (response) {
                    this.current = null;
                    this.mail = null;
                    this.reloadList();
                });
            },
            reloadList: function () {
                this.$http.get('').then(function (response) {
                    this.mails = response.data.mails;
                });
            }
        },
        mounted: function () {
            this.$nextTick(function () {
                var fileToLoad = getParameterByName('file');

                if (fileToLoad) {
                    this.load(fileToLoad)
                }

                this.reloadList()
            })
        }
    })

</script>