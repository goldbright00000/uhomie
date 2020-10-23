export default Vue.extend({

    props: {},

    data() {
        return {
            imagesDir: document.getElementById('images-dir').value,
            _token : document.getElementById('_token').value
        }
    },

    methods: {

        getFilterData: function (name, index) {
            if (this.filters[name])
                for (var item in this.filters[name].options) {
                    if (this.filters[name].options[item].id == index) return this.filters[name].options[item].text
                }
            return ''
        },


    }, watch:  {
       /*editing: function (val, old) {
            if (val) {
                this._cache = Object.assign({}, this.info);
                console.log('editandoooo base')
            }

        }*/

    }
});
