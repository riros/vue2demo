<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>


<div class="attach-block" id="form_file_vue_<?= $arResult['FIELD_ALIAS'] ?>">
  <file-upload_<?= $arParams['FIELD_ID'] ?> :ext_class="file_upload_class"
                                            multiple
                                            action="/request/upload.php"
                                            :btn_text="'Прикрепить материалы ('+file_validators.file_exts+')'"
                                            :id="name_prefix+name_postfix"
                                            :name_prefix='name_prefix'
                                            :name_postfix='name_postfix'
                                            :app_disabled="app_disabled"
                                            :input_file_upload_class='input_file_upload_class'
                                            :init_uploaded_files='init_uploaded_files'
                                            :result_id='result_id'
                                            :file_validators="file_validators"
                                            :add_limit=10
                                            :id="id"
                                            :name="file_upload_element_name"
                                            :sessid="sessid"
                                            :name_alias="name_alias"
  >
  </file-upload_<?= $arParams['FIELD_ID'] ?>>
</div>
<style>
  .upload_progress {
    content: "";
    width: 0;
    height: 5px;
    background-color: #4e9619;
    position: absolute;
    left: 0;
    bottom: 0;
    z-index: 1;
    -webkit-transition: all ease 0.3s;
    -o-transition: all ease 0.3s;
    -moz-transition: all ease 0.3s;
    transition: all ease 0.3s;
  }
</style>

<? if (!defined("REQUEST_FILE_UPLOAD_TEMPLATE")):
  define("REQUEST_FILE_UPLOAD_TEMPLATE", true);
  ?>
  <script type="text/x-template" id="request_file_upload_template">
    <div class="attach-block__files" v-bind:class="[ext_class]">
      <ul>
        <li v-for='(file, index) in uploaded_files'>

          <div class="attach-block__file">
            <div class="upload_progress" ref="file_upload_progress_[file.uid]"
                 v-show="((file.status !=='success') && (file.status !=='error'))"
                 v-bind:style="{ 'width': file.percent+'%' }" v-bind:id="'file_upload_progress_' + file.uid">
            </div>

            <div class="attach-block__file-name">
              <a v-bind:href="file.link" target="_blank" v-bind:id="name_prefix+file.id">
                {{ file.name }}
              </a>
            </div>
            <div class="attach-block__file-size">{{ file.size | prettyBytes }}</div>
            <div class="attach-block__file-status">

              <svg class="icon" v-if="(file.status !== null) && (file.status !== 'progress')" v-bind:class="'icon-' + file.status">
                <use v-bind="{'xlink:href':'<?= MARKUP_PATH ?>/images/sprites.svg#'+file.status}"></use>
              </svg>

            </div>
            <div class="attach-block__file-remove">
              <button v-show="(file.status !== 'progress') && ( !disabled )"
                      v-on:click="_remove_file(file.id);uploaded_files.splice(index,1)" type="button"
                      name="Remove button"
                      v-bind:lid="file.id">
                <svg class="icon icon-remove ">
                  <use xlink:href="<?= MARKUP_PATH ?>/images/sprites.svg#remove"></use>
                </svg>
              </button>
            </div>
          </div>
          <input v-if="file.new === true" v-bind:disabled="file.status !== 'success'" type="hidden"
                 v-bind:name="'file_upload_element__name_alias[]'"
                 :value="name_alias"
          >
          <input v-if="file.new === true" v-bind:disabled="file.status !== 'success'" type="hidden" v-bind:name="name +'__id[]'"
                 :value="file.id"
          >
          <input v-if="file.new === true" v-bind:disabled="file.status !== 'success'" type="hidden" v-bind:name="name +'__name[]'"
                 :value="file.name"
          >
          <input v-if="file.new === true" v-bind:disabled="file.status !== 'success'" type="hidden" v-bind:name="name +'__size[]'"
                 :value="file.size"
          >
          <input v-if="file.new === true" v-bind:disabled="file.status !== 'success'" type="hidden" v-bind:name="name +'__uid[]'"
                 :value="file.uid"
          >
          <input v-if="file.new === true" v-bind:disabled="file.status !== 'success'" type="hidden" v-bind:name="name +'__hash[]'"
                 :value="file.hash"
          >
          <input v-if="file.new === true" v-bind:disabled="file.status !== 'success'" type="hidden" v-bind:name="name +'__status[]'"
                 :value="file.status"
          >
        </li>

      </ul>
      <input v-on:change="fileInputChange" v-bind:id="id || name" v-bind:name="name"
             class="inputfile new_input_file" :class="[input_file_upload_class]" type="file" v-show="false" multiple
             :ref="'file_input_' + id"
             :accept="file_validators.file_exts"
      >

      <slot></slot>

      <div class="attach" v-if="!disabled">
        <label @click="emulate_file_input_click" class="attach-button"
               for="<?= $arResult['NAME_PREFIX'] ?><?= $arResult['FIELD_ALIAS'] ?>">
          <svg class="icon icon-attach ">
            <use xlink:href="<?= MARKUP_PATH ?>/images/sprites.svg#attach"></use>
          </svg>
          {{ btn_text }}
        </label>

      </div>

      <div v-else-if="uploaded_files.length === 0" style="text-align: center; padding-bottom: 20px; padding-top: 20px">
        <span>файлы отсутствуют</span>
      </div>
    </div>
  </script>
<? endif; ?>

<script>


    BX.Vue.component('file-upload_<?=$arParams['FIELD_ID']?>', {
        template: "#request_file_upload_template",
        props: {
            ext_class: String,
            name: {
                type: String,
                required: true
            },
            name_alias: {
                type: String,
                Required: true,
            },
            id: String,
            action: {
                type: String,
                required: true
            },
            accept: String,
            multiple: String,
            headers: Object,
            name_prefix: String,
            name_postfix: String,
            input_file_upload_class: {
                type: String,
                required: true
            },
            method: String,
            btn_text: {
                type: String,
                default: 'Upload'
            },
            app_disabled: {
                type: Boolean,
                default: false
            },
            init_uploaded_files: {
                type: Array,
                required: true
            },
            result_id: {
                type: Number,
                required: true
            },
            file_validators: {
                type: Object,
                required: false
            },
            add_limit: {
                type: Number,
                default: 10
            },
            sessid: String
        },

        data: function () {
            return {

                newFiles: {},
                uploaded_files: this.init_uploaded_files,
                disabled: this.app_disabled,

                class: this.ext_class,
            };
        },

        created: function () {
        },
        methods: {
            humanFileSize: function (bytes, si = true) {
                var thresh = si ? 1000 : 1024;
                if (Math.abs(bytes) < thresh) {
                    return bytes + ' Б';
                }
                var units = si
                    ? ['кБ', 'МБ', 'ГБ', 'ТБ', 'PB', 'EB', 'ZB', 'YB']
                    : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
                var u = -1;
                do {
                    bytes /= thresh;
                    ++u;
                } while (Math.abs(bytes) >= thresh && u < units.length - 1);
                return bytes.toFixed(1) + ' ' + units[u];
            },
            uuid() {
                return 'xxxxxxxxxxxx4xxxyxxxxxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                    var r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
                    return v.toString(16);
                });
            },
            _remove_file: function (file_id) {
                this.$emit('onBeforeRemoveFile', file_id);
                var xhr = new XMLHttpRequest();
                return new Promise(function (resolve, reject) {
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState < 4) {
                            return;
                        }
                        if (xhr.status < 400) {
                            try {
                                var res = JSON.parse(xhr.responseText);
                                this.$root.$emit('onFileRemoved', file_id, res);
                                resolve(file_id);
                                this.$forceUpdate();
                            } catch (e) {
                                var err = {};
                                err.status = 500;
                                err.statusText = 'Ошибка сети';
                                this.$root.$emit('onFileRemoveError', file_id, err);
                                // file.status = 'error';
                                reject(err);
                            }
                        } else {
                            var err = JSON.parse(xhr.responseText);
                            err.status = xhr.status;
                            err.statusText = xhr.statusText;
                            this.$root.$emit('onFileRemoveError', file_id, err);
                            // file.status = 'error';
                            reject(err);
                        }
                    }.bind(this);

                    xhr.onerror = function () {
                        try {
                            var err = JSON.parse(xhr.responseText);
                        } catch (e) {
                            err = {};
                        }
                        err.status = xhr.status;
                        err.statusText = xhr.statusText;
                        this.$root.$emit('onFileRemoveError', file, err);
                        // file.status = 'error';
                        reject(err);

                    }.bind(this);

                    xhr.open('GET', '/request/del.php?file_id=' + file_id + '&RESULT_ID=' + this.result_id + '&ALIAS=' + this.name_alias);
                    xhr.setRequestHeader('X-Bitrix-Csrf-Token', this.sessid);
                    xhr.send();
                    this.$root.$emit('onAfterRemoveFile', file_id);
                }.bind(this))
            },
            emulate_file_input_click(e) {
                this.$refs['file_input_' + this.id].removeAttribute('disabled');

                if (e.target !== this.$refs['file_input_' + this.id]) {
                    e.preventDefault();
                    this.$refs['file_input_' + this.id].click();
                }

            },
            fileInputClick: function () {

                this.$root.$emit('onFileClick', this.newFiles);
            },
            fileInputChange: function () {
                // get the group of files assigned to this field
                let ident = this.id || this.name;
                this.newFiles = document.getElementById(ident).files;

                this.$root.$emit('onFileChange', this.newFiles);
                for (let key in this.newFiles) {
                    let vuuid = this.uuid();
                    let new_file = this.newFiles[key];
                    new_file['status'] = null;
                    new_file['uid'] = vuuid;
                    new_file['new'] = true;
                    if ((Number(key) > 0) || (key === '0')) {
                        if (new_file.size <= this.file_validators.size_from) {
                            let a = this.humanFileSize(this.file_validators.size_from);
                            alert('Ошибка! Размер файла меньше, чем ' + a);
                            continue;
                        }
                        if (new_file.size > this.file_validators.size_to) {
                            let a = this.humanFileSize(this.file_validators.size_to);
                            alert('Ошибка! Размер файла больше, чем ' + a);
                            continue;
                        }
                        this.uploaded_files.push(new_file);
                    }
                }
                this.fileUpload();
            },
            fileUpload: function () {
                if (this.newFiles.length > 0) {
                    // a hack to push all the Promises into a new array
                    let arrayOfPromises = Array.prototype.slice.call(this.newFiles, 0).map(function (file) {
                        this.disabled = true;
                        // console.log("map handle");
                        return this._handleUpload(file);
                    }.bind(this));
                    // wait for everything to finish
                    Promise.all(arrayOfPromises).then(function (allFiles) {
                        if (this.app_disabled === false)
                            this.disabled = false;
                        this.$root.$emit('onAllFilesUploaded', allFiles);
                        // this.$root.$forceUpdate()
                    }.bind(this)).catch(function (err) {
                        if (this.app_disabled === false)
                            this.disabled = false;
                        this.$root.$emit('onFileError', this.newFiles, err);
                    }.bind(this));
                } else {
                    // someone tried to upload without adding files
                    let err = new Error("No files to upload for this field");
                    this.$root.$emit('onFileError', this.newFiles, err);
                }
            },
            _handleUpload: function (file) {
                this.$root.$emit('beforeFileUpload', file);
                let form = new FormData();
                let xhr = new XMLHttpRequest();
                try {
                    form.append('Content-Type', file.type || 'application/octet-stream');
                    // our request will have the file in the ['file'] key
                    form.append('file', file);
                } catch (err) {
                    this.$root.$emit('onFileError', file, err);
                    return;
                }

                return new Promise(function (resolve, reject) {

                    xhr.upload.onprogress = function (e) {
                        // e.percent = (e.loaded / e.total) * 100;
                        file.status = 'progress';
                        file.percent = (e.loaded / e.total) * 100;
                        this.$root.$emit('onFileProgress', file);
                        let el = document.getElementById('file_upload_progress_' + file.uid);
                        el.style.width = file.percent + '%';
                        // console.log(file.uid, file.percent, file.status);
                        // this.$root.$forceUpdate();
                    }.bind(this);
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState < 4) {
                            file.status = 'progress';
                            return;
                        }
                        if (xhr.status < 400) {
                            try {
                                let res = JSON.parse(xhr.responseText);
                                this.$root.$emit('onFileUpload', file, res);
                                file.status = 'success';
                                file.id = res.file_id;
                                resolve(file);
                                // this.$root.$forceUpdate();
                            } catch (e) {
                                let err = {};
                                err.status = 500;
                                // xhr.status = 500;
                                err.statusText = 'Ошибка ' + e.value;
                                file.status = 'error';
                                this.$root.$emit('onFileError', file, err);
                                reject(err);
                                this.$root.$forceUpdate();

                            }
                        } else {
                            try {
                                let err = JSON.parse(xhr.responseText);
                            } catch (e) {
                                err = {'error': e.msg};
                            }
                            err.status = xhr.status;
                            err.statusText = xhr.statusText;
                            this.$root.$emit('onFileError', file, err);
                            file.status = 'error';
                            reject(err);
                            this.$root.$forceUpdate();

                        }
                    }.bind(this);

                    xhr.onerror = function () {
                        try {
                            var err = JSON.parse(xhr.responseText);
                        } catch (e) {
                            err = {};
                        }
                        err.status = xhr.status;
                        err.statusText = xhr.statusText;
                        this.$root.$emit('onFileError', file, err);
                        file.status = 'error';
                        reject(err);

                    }.bind(this);

                    xhr.open(this.method || "POST", this.action, true);
                    xhr.setRequestHeader('X-Bitrix-Csrf-Token', this.sessid);
                    if (this.headers) {
                        for (let header in this.headers) {
                            if (this.headers.hasOwnProperty(header))
                                xhr.setRequestHeader(header, this.headers[header]);
                            else console.log('Ошибка в логике!');
                        }
                    }
                    try {
                        xhr.send(form);
                    } catch (e) {
                        console.log('Post send Error! ' + e.msg);
                    }
                    this.$root.$emit('afterFileUpload', file);
                }.bind(this));
            }
        }
    });

    BX.Vue.filter('prettyBytes', function (num) {
        // jacked from: https://github.com/sindresorhus/pretty-bytes
        num = Number(num);
        if (typeof num !== 'number' || isNaN(num)) {
            throw new TypeError('Expected a number');
        }

        var exponent;
        var unit;
        var neg = num < 0;
        var units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        if (neg) {
            num = -num;
        }

        if (num < 1) {
            return (neg ? '-' : '') + num + ' B';
        }

        exponent = Math.min(Math.floor(Math.log(num) / Math.log(1000)), units.length - 1);
        num = (num / Math.pow(1000, exponent)).toFixed(2) * 1;
        unit = units[exponent];

        return (neg ? '-' : '') + num + ' ' + unit;
    });

    let app__<?=$arResult['FIELD_ALIAS']?>__<?=$arResult['FIELD_ID']?> = BX.Vue.create({
        el: '#form_file_vue_<?=$arResult['FIELD_ALIAS']?>',
        data: {
            //availavle_for_add: [<?//=$arResult['FILES_JS_ARRAY_STRING']?>//],
            app_disabled: <?=Cutil::PhpToJSObject($arResult['DISABLED'])?>,
            name_prefix: "<?=$arResult['NAME_PREFIX']?>_",
            name_postfix: "<?=$arResult['FIELD_ALIAS']?>__<?=$arResult['FIELD_ID']?>",
            name_alias: "<?=$arResult['FIELD_ALIAS']?>",
            input_name_prefix: 'form_input_file__',
            name_prefix: 'form_a_file__',
            // size_name_prefix: 'form_size_file_',
            file_upload_class: 'file_upload_component__<?=$arResult['FIELD_ALIAS']?>__<?=$arResult['FIELD_ID']?>',
            input_file_upload_class: 'uploader_input_file__<?=$arResult['FIELD_ID']?>',
            file_upload_element_name: 'file_upload_element__<?=$arResult['FIELD_ALIAS']?>',
            init_uploaded_files: <?=Cutil::PhpToJSObject($arResult['UPLOADED_FILES_INFO'])?> ,
            result_id: <?= (int)$arResult['RESULT_ID']?>,
            file_validators: <?= Cutil::PhpToJSObject($arResult['FILE_VALIDATORS']) ?>,
            id: <?=$arResult['FIELD_ID']?>,
            sessid: '<?=bitrix_sessid_val()?>',
        },
        mounted: function () {
        },
        created: function () {


            this.$root.$on('onFileUpload', this.onFileUpload);

        },
        beforeDestroy: function () {


            this.$root.$off('onFileUpload', this.onFileUpload);

        },
        methods: {
            onFileClick: function (file) {
                console.log('onFileClick', file);
            },
            onFileChange: function (file) {

            },
            beforeFileUpload: function (file) {

            },
            afterFileUpload: function (file) {

            },
            onFileProgress: function (progress) {

            },
            onFileUpload: function (file, res) {

            },
            onFileError: function (file, res) {
                console.error('onFileError', file, res);
            },
            onAllFilesUploaded: function (files) {
                this.allFilesUploaded = true;
            },
            file_obj_by_id: function (arr, id) {
                let ret = undefined;
                for (let i = 0; i < arr.length; i++) {
                    if (arr[i].id === id) {
                        ret = arr[i];
                        break;
                    }
                }
                return ret;
            },
            index_by_id: function (arr, id) {
                for (let i = 0; i < arr.length; i++) {
                    if (arr[i].id === id) {
                        return i;
                    }
                }
                return undefined;
            },

        }
    });
</script>
