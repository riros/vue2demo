import commonjs from 'rollup-plugin-commonjs'; // Конвертирование CommonJS модулей в ES6
import vue from 'rollup-plugin-vue'; // Обработка однофайловых компонентов .vue
// import buble from 'rollup-plugin-buble'; // Транспиляция/добавление полифилов для умеренной поддержки браузеров
// import coffeescript from 'rollup-plugin-coffee-script';

export default {
    input: 'src/wrapper.js', // Путь до относительного package.json
    output: {
        name: 'CustomTable',
        exports: 'named'
    },
    // external: ['Vue', 'vue-multiselect', "vuex", 'vuex-models', "vuex-persistedstate"],
    plugins: [
        commonjs({
            // extensions: ['.js', '.coffee'],
        }),
        vue({
            css: true, // Динамически внедряем CSS в тег <style>
            compileTemplate: true, // Явное преобразование шаблона в рендер-функцию
        }),
        // coffeescript(),
        // buble(), // Транспиляция в ES5
        // coffee2({
        //     bare: true,
        //     extensions: ['.coffee', '.litcoffee'],
        //     version: 'auto',
        //     sourceMap: true
        // })
    ],
};
