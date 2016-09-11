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
            currentFile: null,
            mails: [],
            textVersionShown: false
        },
        computed: {
            'iframe_content': function () {
                return "data:text/html;charset=utf-8," + rawurlencode(this.mail.parts[0].content);
            },
            'text_content': function () {
                if (!this.mail.parts[1]) {
                    return null;
                }
                return this.mail.parts[1].content;
            }
        },
        methods: {
            load: function (file) {
                this.currentFile = file;
                this.$http.get(file).then(function (response) {
                    this.mail = response.data;
                    this.textVersionShown = false;
                });
            },
            isActive: function (mail) {
                return this.currentFile === mail.file;
            },
            emlPath: function () {
                return "/mail-mango/download/" + this.currentFile;
            },
            deleteAll: function () {
                this.$http.delete('all').then(function (response) {
                    this.reloadList();
                });
            },
            deleteCurrent: function () {
                this.$http.delete(this.currentFile).then(function (response) {
                    this.currentFile = null;
                    this.mail = null;
                    this.textVersionShown = false;
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