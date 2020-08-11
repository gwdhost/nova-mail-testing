<template>
    <div>
        <heading class="mb-6">Mail Testing</heading>

        <div class="form-group w-1/2 pr-4">
            <label class="form-label">Choose mail to test:</label>
            <select v-model="selected_mail_id" class="form-control">
                <option value="">Choose the mail</option>
                <option v-for="mail in mails" :key="mail.id" :value="mail.id">{{ mail.name }}</option>
            </select>
        </div>

        <div class="form-group mb-3" v-if="mail && mail.description" v-html="mail.description"></div>

        <div v-if="mail" class="flex mail-template">
            <div class="mails-fields mb-3 w-1/2 pr-4">
                <div class="mail-field" v-for="(arg, index) in mail.args" :key="arg.id">
                    <div class="form-group mail-field__wrapper mail-field__wrapper--nova" v-if="arg.nova">
                        <label class="form-label mail-field__label">Mail class argument {{ index + 1 }}: ({{ arg.label || arg.model }})</label>

                        <select class="form-control" v-model="arg.value">
                            <option value="">Choose option</option>
                            <option v-for="item in arg.items" :key="item.id" :value="item.id">{{ item[arg.title] }}</option>
                        </select>
                    </div>
                    <div class="form-group mail-field__wrapper mail-field__wrapper--custom" v-if="arg.type && arg.type !== 'hidden'">
                        <label class="form-label mail-field__label">Mail class argument {{ index + 1 }}: ({{ arg.label }})</label>

                        <select class="form-control" v-model="arg.value" v-if="arg.type === 'select'">
                            <option value="">Choose option</option>
                            <option v-for="(label, value) in arg.options" :key="value" :value="value">{{ label || value }}</option>
                        </select>

                         <input type="text" class="form-control" :placeholder="arg.placeholder" v-model="arg.value" v-if="arg.type === 'text'" />

                         <textarea :rows="arg.rows || 3" class="form-control" :placeholder="arg.placeholder" v-model="arg.value" v-if="arg.type === 'textarea'" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Email to send email to:</label>
                    <input type="email" class="form-control" v-model="mail.email" />
                </div>

                <button class="btn btn-default btn-primary" @click.prevent="handleSendMail">Send mail</button>

                <button class="btn btn-default btn-primary ml-2" @click.prevent="handleViewMail">View mail</button>
            </div>

            <div class="mail-test-frame pl-4" v-if="mailFrameSrc">
                <iframe ref="mail-frame" :src="mailFrameSrc" />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            mails: [],
            mailFrameSrc: null,
            loading: false,
            t: 0,
            selected_mail_id: null
        };
    },
    computed: {
        mail(){
            if(this.selected_mail_id){
                return this.mails.find(x => x.id === this.selected_mail_id);
            }

            return null;
        }
    },
    methods: {
        handleSendMail(){
            const mail = this.mail;

            this.loading = true;
            axios
                .post("/nova-vendor/mail-testing/send-email", {
                    mail_id: mail.id,
                    email: mail.email,
                    args: mail.args.map(arg => arg.value),
                }).then((rsp) => {
                    this.loading = false;
                    console.log('hejsa', rsp);
                }).catch(() => {
                    this.loading = false;
                });
        },
        handleViewMail(){
            const mail = this.mail;
            this.t += 1;

            let mailFrameSrc = "/nova-vendor/mail-testing/view-email/?mail_id=" + mail.id + "&t=" + this.t;

            mail.args.forEach(arg => {
                mailFrameSrc += '&args[]=' + arg.value;
            });

            this.mailFrameSrc = mailFrameSrc;
        }
    },
    mounted() {
        axios
            .get("/nova-vendor/mail-testing/mails")
            .then(({ data: { data = [] } = {} }) => {
                this.mails = data;
                console.log("hejsa", data);
            });
    }
};
</script>

<style lang="scss" scoped>
/* Scoped Styles */
.mail-test-frame iframe {
    width: 100%;
    height: 500px;
    border: 1px solid #ccc;
}

.mail-template {
    display: flex;
    align-items: stretch;
    justify-content: flex-start;
    flex-wrap: wrap;
}

.mail-test-frame {
    flex-grow: 1;
}

.form-group {
    margin: 12px 0 18px;
}
.form-label {
    display: block;
    font-weight: bold;
    color: #111;
    margin: 0 0 5px 0;
}
.form-control {
    width: 100%;
    display: block;
    border: 1px solid #cccccc;
    background: #ffffff;
    color: #000;
    padding: 0 10px;
    min-height: 40px;
}
textarea.form-control {
    height: auto;
    padding-top: 8px;
    padding-bottom: 8px;
    max-width: 100%;
    min-width: 100%;
}
</style>
