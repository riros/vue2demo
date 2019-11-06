<template>
  <div class="form-field narkp"
       :class="formFieldClasses()"
  >
    <label :for="name" class="form-placeholder">{{ label }}</label>
    <multiselect :id="name" :name="name"
                 v-model="model"
                 :options="options"
                 :multiple="multiple"
                 placeholder="Выберите..."
                 :label="false"
                 track-by="name"
                 label="name"
                 :close-on-select="multiple? false: true"
                 :class="[status()]"
                 deselect-label="Нажмите enter для удаления"
                 deselect-group-label="Нажмите enter для удаления группы"
                 select-label="Нажмите enter для выбора"
                 tag-placeholder="Нажмите enter для создания тега"
                 selected-label="Выбрано"
                 :allowEmpty="required? false: true"
                 :optionsLImit="1000"
                 :limit="limit"
                 :limit-text="limitText"
                 v-on:input="$emit('input',model)"
    >
      <span slot="noResult">Ничего не найдено...</span>
    </multiselect>
    <!--    <div class="form-field__icon"></div>-->
    <div></div>
  </div>
</template>

<script>
    import Vuelidate from 'vuelidate'
    import {
        required,
        minLength,
        maxLength,
        email
    } from 'vuelidate/lib/validators'


    import Multiselect from 'vue-multiselect';

    export default {
        // el: '#customlist',
        name: "custom-multiselect-field",
        components: {
            Multiselect,
        },
        props: {
            value: [Object, Array],
            options: {
                type: Array,
                Required: true,
            },
            allowEmpty: {
                type: Boolean,
                Default: false,
            },
            label: {
                type: String,
                Required: true
            },
            multiple: {
                type: Boolean,
                Default: false
            },
            required: {
                type: Boolean,
                Default: false
            },
            limit: {
                type: Number,
                Default: 1000,
            },
            // model: {
            //     type: Object,
            //     Required: true
            // },
            name: {
                type: String,
                Required: true,
            },
            dirty: Boolean,
            error: Boolean,
            // limitText: String

        },
        // vuetify: new Vuetify(),
        data() {
            return {
                model: this.value,
                // errorstr: 'ok',
                // options: [
                //     {'name': 'Республика Адыгея (Адыгея)', 'id': '28'},
                //     {
                //         'name': 'Республика Башкортостан',
                //         'id': '29'
                //     },
                // ],
                // user: {
                // uf_subject_rf: '',
                // name: '',
                // phone: '',
                // email: '',
                // city: '',
                // }
                // user: {
                //     uf_subject_rf: '',
                //     username: '',
                //     phone: '',
                //     email: '',
                //     city: '',
                // },
                // message: {show: false, text: '', type: null},

            }
        },
        // validations: {
        //     user: {
        //         uf_subject_rf: {
        //             required
        //         },
        //         username: {
        //             required
        //         },
        //         phone: {
        //             required,
        //             minLength: minLength(10)
        //         },
        //         email: {
        //             email
        //         },
        //         city: {
        //             required,
        //             minLength: minLength(3),
        //         }
        //     }
        // },
        methods: {
            limitText(count) {
                return `+ ${count}`;
            },
            status() {
                return {
                    error: this.error,
                    dirty: this.dirty,
                }
            },
            formFieldClasses() {
                return [this.error ? 'is-error' : '', (this.dirty && !this.error) ? 'is-success' : ''];
            },
            // checkForm: function (e) {
            //     e.preventDefault();
            //     console.log(this.$v.$anyError);
            //     if (this.$v.$anyError) this.showError('Ошибка отправки формы');
            //     else this.showSuccess("Сохранено");
            // },
            showError: function (msg) {
                this.message.type = 'error';
                this.message.show = true;
                this.message.text = msg;
            },
            showSuccess: function (msg) {
                this.message.type = 'success';
                this.message.show = true;
                this.message.text = msg;

            }


        }
    }
</script>

<style>

</style>
