<template>
    <div>
        <!--    <div class="form-subtitle">Заявки</div>-->
        <div class="apl-options">
            <slot name="aplnew">
                <div class="apl-new">
                    <a class="apl-new__button" href="/request/new.php">
                <span>
                  <svg class="icon icon-plus ">
                  <use
                          v-bind="{'xlink:href': static_path + '/images/sprites.svg#plus'}"></use>
                  </svg>
                </span> Подать новую заявку </a>
                </div>
            </slot>

            <slot name="aplinfo">
                <div class="apl-info">
                    <!--      TODO: сделать фильтр только активных и всех по нажатии, выделение выбранного зеленым кружоком.-->
                    <div class="apl-info-active">Активные заявки:
                        <span
                                style="padding-left: 10px"><strong> {{ active_count }}</strong> </span>
                    </div>
                    <div class="apl-info-total apl-info-active">Общее кол-во заявок: <span
                            class="green">{{ rows.length }}</span>
                    </div>
                </div>
            </slot>

        </div>
        <!--    <div style="height: 20px"></div>-->

        <!--      <div class="js-filter-button filter-button">Фильтр</div>-->
        <div class="moder-search-filter" v-show="linesCount > 0">
            <div class="form-grid">
                <div class="form-col-4 fio-choice">
                    <div class="form-field">
                        <input class="form-input search-input" type="text" name="fio"
                               placeholder="Поиск ..."
                               v-model="search_text">
                    </div>
                </div>
            </div>
        </div>
        <div class="moder-filter" v-show="show_panel && linesCount > 0">
            <div class="moder-filter__title">Фильтр
                <div class="js-filter-close moder-filter__close">
                    <svg class="icon icon-arrow_left ">
                        <use
                                v-bind="{'xlink:href': static_path + '/images/sprites.svg#arrow_left'}"

                        ></use>
                    </svg>
                </div>
            </div>

        </div>
        <!--            common-->
        <div class="apl-table" v-if="linesCount > 0"
             :class="[(Object.keys(cols).length > 5 ? 'expanded':'')]"
        >
            <div class="apl-table__status-select">
                <!--    <template v-slot:mobilemenu>-->
                <!--      <select class="js-select" name="status">-->
                <!--        <option value="">Все</option>-->
                <!--        <option value="finished">Завершенные</option>-->
                <!--        <option value="process">В работе</option>-->
                <!--      </select>-->
                <!--    </template>-->
            </div>
            <table>
                <thead>
                <tr>

                    <th v-for="(col, key) in cols"
                        :class=" (key in sortable_fields)? 'button': ''"
                        @click="(key in sortable_fields)? change_sort(key): nope(key)">
                        <div class="div-flex">
                            <div>
                                <svg v-if="((key in sortable_fields) && (key === sortKey ))"
                                     class="icon"
                                     :class="[( (key in sortable_fields) ? 'icon-'+( (sortOrders[key] < 0)? 'arrow_up':'arrow_down'):null)]">
                                    <use
                                            v-bind="{'xlink:href': static_path + '/images/sprites.svg' + ((sortOrders[key]) < 0? '#arrow_up':'#arrow_down')}"></use>
                                </svg>
                                <span v-else-if="(key in sortable_fields)">*</span>
                            </div>
                            <div>{{ col.title | capitalize }}</div>
                        </div>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(row, rkey) in paginated_rows" :key="rkey">
                    <td v-for="(col, ckey) in cols" :key="ckey"
                        @click="( findLink(col['filters']) ? openLink(findLink(col['filters'])+row[index_key]): openRequest(row) )"
                        :inner-html.prop="format(row[index_key], row[ckey], col['filters'])"></td>
                </tr>
                </tbody>
            </table>
            <div class="apl-table-paginator">
                <div class="apl-table-quantity"><span>Показывать по</span>
                    <multiselect
                            :options="lines_on_page_options"
                            :multiple="false"
                            :searchable="false"
                            v-model="lines_on_page_count"
                            :close-on-select="true"
                            class="select_lines_on_page"
                            deselect-label=""
                            deselect-group-label=""
                            select-label=""
                            tag-placeholder=""
                            selected-label=""
                            :allowEmpty="false"
                    ></multiselect>
                    <!--                    <select class="js-select" :class="['js-select-lines-on-page']" v-model="lines_on_page_count"-->
                    <!--                            v-bind:value="lines_on_page_count"-->
                    <!--                            ref="lines_on_page_select_ref">-->
                    <!--                        <option v-for="opt in lines_on_page_options" v-bind:id="opt.id" v-bind:value="opt.value"-->
                    <!--                                v-bind:data-value="opt.value">{{ opt.value }}-->
                    <!--                        </option>-->
                    <!--                    </select>-->
                </div>
                <div class="apl-table-page">
                    <div class="apl-table-page__num">{{ Number(pageNumber) + 1 }} из {{
                        pageCount }}
                    </div>
                    <div class="apl-table-nav">
                        <button class="apl-table__prev" @click="prevPage" type="button"
                                :disabled="pageNumber === 0"></button>
                        <button class="apl-table__next" @click="nextPage" type="button"
                                :disabled="pageNumber >= pageCount -1"></button>
                    </div>
                </div>
            </div>
        </div>

        <div v-else>
            <div class="apl-table empty"
                 :class="(Object.keys(cols).length > 5 ? 'expanded':'')">

                <table>
                    <thead>
                    <tr>

                        <th v-for="(col, key) in cols"
                            :class=" (key in sortable_fields)? 'button': ''"
                            @click="(key in sortable_fields)? change_sort(key): nope(key)">
                            <div class="div-flex">
                                <div>
                                    <svg v-if="((key in sortable_fields) && (key === sortKey ))"
                                         class="icon"
                                         :class="[( (key in sortable_fields) ? 'icon-'+( (sortOrders[key] < 0)? 'arrow_up':'arrow_down'):null)]">
                                        <use
                                                v-bind="{'xlink:href': static_path + '/images/sprites.svg' + ((sortOrders[key]) < 0? '#arrow_up':'#arrow_down')}"
                                        ></use>
                                    </svg>
                                </div>
                                <div>{{ col.title | capitalize }}</div>
                            </div>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td v-bind:colspan="Object.keys(cols).length">
                            <svg class="icon icon-stop ">
                                <use
                                        v-bind="{'xlink:href': static_path + '/images/sprites.svg#stop'}"></use>
                            </svg>
                            <p>Нет данных для отображения</p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    // import Vue from 'vue';
    import Multiselect from 'vue-multiselect';
    import {mapVuexModels, genVuexModels} from 'vuex-models';

    const staticModels = genVuexModels({
        lines_on_page_count: 10,
        pageNumber: 0,
        sortKey: null,
        search_text: '',
        sortOrders: {},

    });
    export default {
        name: 'CustomTable',
        components: {
            Multiselect,
        },
        staticModels: staticModels,
        // store,
        // Store: customTableStore,
        props: {
            rows: Array,
            cols: {
                type: Object,
                Required: true
            },
            // initial_lines_on_page: Number,
            lines_on_page_options: Array,
            // active_count: Number,
            total_unfiltered_count: Number,
            open_link: String,
            open_link__field_name: String,
            // name: String,
            uid: {
                type: String,
                Required: true
            },
            app_name: {
                type: String,
                Required: true,
            },
            sortable_fields: Object,
            back_uri: String,
            // searchable_field_names: Array,
            filterable_field_names: Array,
            show_panel: Boolean,
            static_path: {
                type: String,
                Required: true
            },
            show_filters: {
                type: Boolean,
                default: true,
            },
            show_prolog: {
                type: Boolean,
                default: true,
            },


        },
        data: function () {

            // var initSortOrders = {};
            // for (let key in this.cols) {
            //     initSortOrders[key] = this.sortable_fields[key] ? 1 : -1;
            // }

// this.cols.forEach(function (key) {
//     sortOrders[key] = 1
// });
            var index_key = undefined;
            for (let key in this.cols) {
                if (this.cols.hasOwnProperty(key) && this.cols[key].hasOwnProperty('key') && this.cols[key]['key'] === true) {
                    index_key = key;
                    break
                }
            }
            if (index_key === undefined) {
                console.error('Vue:customlist: Не задано поле в качестве индекса. Указаны обяхательыне параметы компонента?');
            }
            // ----------------------


            const uniqueArray = function (array) {
                return Array.from(new Set(array));
            };
            let filter_opts = {};
            // TODO сделать динамическую подгрузку фильтров из параметров.
            let filter_selected = {
                // 'get_category': this.filter_string_form_localstore('get_category'),
                // 'get_busy': this.filter_string_form_localstore('get_busy'),
                // 'get_org_type_name': this.filter_string_form_localstore('get_org_type_name'),
            };
            for (let i in this.filterable_field_names) {
                if (this.rows.hasOwnProperty(i)) {
                    let key = this.filterable_field_names[i];
                    // filter_selected[key] = un;
                    let key_col = [];
                    for (let k in this.rows) {
                        key_col.push(this.rows[k][key]);
                    }
                    filter_opts[key] = uniqueArray(key_col);
                    // console.log(key_col, filter_options);
                }
            }

            // -----------------------------
            return {
                // static_path:'vendor/markup/',
                // pageNumber: 0,
                // lines_on_page_count: this.initial_lines_on_page,
                // sortOrders: initSortOrders,
                index_key: index_key,
                // search_text: this.load_search(),
                filter_opts: filter_opts,
                filter_selected: filter_selected,
                // test_options: [
                //     1, 2, 3, 4, 5, 6, 7, 8, 9, 43, 23, 678, 233
                // ],


                // settter_getter_localstore_sync: function (key_name) {
                //     return {
                //
                //         get() {
                //             let val = this.load_string_form_localstore(key_name);
                //             return val ? val : this[key_name + '_default'];
                //         },
                //         set(value) {
                //             localStorage[this.app_name + '_' + key_name] = value;
                //         }
                //
                //     }
                // }


                // filter_get_category: null,
                // sortable_fields: this.sortable_fields
            }
        },
        mounted: function () {
            let sortOrders = {};
            for (let key in this.cols) {

                if (this.sortOrders.hasOwnProperty(key) === false) {
                    sortOrders[key] = this.sortable_fields[key] ? 1 : -1;
                    //         console.log('add ',key, this.sortOrders[key]);
                } else {
                    sortOrders[key] = this.sortOrders[key];
                }
                //
            }
            this.sortOrders = sortOrders;
        },
        created: function () {

        },
        updated: function () {

        },
        watch: {
            lines_on_page_count(new_value) {
                this.pageNumber = 0;
            },

            search_text(newValue) {
                this.pageNumber = 0;

            },

        },
        filters: {
            capitalize: function (str) {
                return str.charAt(0).toUpperCase() + str.slice(1)
            },
            readMore: function (id, text, length, suffix) {
                return text.length > length ? text.substring(0, length) + ' ' + suffix : text;
            },
            ds_list_link: function (id, text, url) {
                return '<span class="max_size type_link">' + text + '</span>';

            },
            substring: function (id, text, begin, end) {
                return text.substring(begin, end);
            },
            ds_list_currencyPostfix: function (id, text, begin, end) {
                return text.substring(begin, end);
            },


        },
        methods: {
            format: function (id, value, filters) {
                let ret = value;
                for (let item in filters) {
                    if (filters.hasOwnProperty(item))
                        if (filters[item].hasOwnProperty('param') && filters[item].hasOwnProperty('name'))
                            ret = this.$options.filters[filters[item].name](id, ret, ...filters[item].param);
                        else if (filters[item].hasOwnProperty('name'))
                            ret = this.$options.filters[filters[item].name](id, ret);

                }
                return ret;
            }
            ,
            alert: function (param) {
                alert(param);
            }
            ,
            nope: function (param) {
                // заглушка. ничего не делает
            }
            ,
            change_sort(key) {
                this.sortOrders[key] = this.sortOrders[key] * -1;
                this.sortKey = key;
            },


            short_datatime: function (dt) {
                return dt.slice(0, 10);
            }
            ,
            nextPage: function () {
                this.pageNumber++;

            }
            ,
            prevPage: function () {
                this.pageNumber--;
            },
            openRequest: function (p) {
                window.location = this.open_link + p[this.open_link__field_name];
            }
            ,
            openLink: function (link) {
                window.location = (link + '&BACK_URI=' + this.back_uri);
            }
            ,
            findLink: function (filters) {
                let ret = null;
                for (let i in filters) {
                    if (filters.hasOwnProperty(i))
                        if (filters[i]['name'] === 'ds_list_link')
                            ret = filters[i]['param'][0];
                }
                return ret;
            },

        },
        computed: {
            // тут аналог статических моделей, которые синхронизируются с localStore
            // обязательно надо передавать имя модели в параметрах
            ...mapVuexModels({
                lines_on_page_count: 'lines_on_page_count',
                pageNumber: 'pageNumber',
                sortKey: 'sortKey',
                search_text: 'search_text',
                sortOrders: 'sortOrders'

            }, 'expertPage'),

            // -------------
            active_count: function () {
                return this.filtered_and_sorted_rows.filter(function (el) {
                    return el.active;
                }).length;
            }
            ,
            linesCount: function () {
                // return this.rows.length;
                return this.filtered_and_sorted_rows.length;
            }
            ,
            pageCount: function () {
                let l = this.filtered_and_sorted_rows.length,
                    s = this.lines_on_page_count;
                return Math.ceil(l / s);
            }
            ,
            paginated_rows: function () {
                let start = this.pageNumber * this.lines_on_page_count;
                let end = start + this.lines_on_page_count;
                return this.filtered_and_sorted_rows.slice(start, end);
            }
            ,
            filtered_and_sorted_rows: function () {
                let sortKey = this.sortKey;
                let order = this.sortOrders[sortKey] || 1;
                let search_text = this.search_text && this.search_text.toLowerCase();
                let filter_selected = this.filter_selected;
                let local_rows = this.rows;
                //
                if (search_text) {
                    local_rows = local_rows.filter(function (row) {
                        return Object.keys(row).some(function (key) {
                            let srh_number = parseFloat(search_text);
                            let data_number = parseFloat(row[key]);
                            if (!isNaN(srh_number) && isFinite(search_text)) {
                                if (!isNaN(data_number) && isFinite(row[key])) {
                                    return srh_number === data_number;
                                } else return false;
                            } else {
                                return String(row[key]).toLowerCase().indexOf(search_text) > -1;
                            }
                        })
                    })
                }

                if (Object.keys(filter_selected).some(function (filter_key) {
                    return filter_selected[filter_key];
                })) {
                    local_rows = local_rows.filter(function (row) {
                            return Object.keys(filter_selected).every(function (filter_key) {
                                return filter_selected[filter_key] !== 'undefined' && filter_selected[filter_key] ? (row[filter_key] === filter_selected[filter_key]) : true;
                            })
                        }
                    )
                }

                if (this.sortKey) {
                    local_rows = local_rows.slice().sort(
                        function (a, b) {
                            a = a[sortKey];
                            b = b[sortKey];
                            return (a === b ? 0 : a > b ? 1 : -1) * order;
                        }
                    )
                }
                return local_rows;
            }
        }
        ,

    }
</script>

<style>
    .icon-arrow_up {
        width: 18px;
        height: 18px;
    }

    .div-flex {
        display: flex;
    }

    td a {
        color: #007eb4;
    }


    .max_size {
        width: 100%;
        height: 100%;

    }

    .type_link {
        color: #007eb4;
        cursor: pointer;

    }

    .apl-table tr td {
        cursor: pointer;

    }

    select:invalid {
        color: grey;
    }

</style>
