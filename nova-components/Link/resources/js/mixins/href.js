export default {

    computed: {

        href() {
console.log(this);
            let url = this.field.template.replace(':resource', this.resourceName);
            url = url.replace(':id', this.resourceId);
            url = url.replace(':viaResource', this.viaResource ? this.viaResource : '');
            url = url.replace(':viaResourceId', this.viaResourceId ? this.viaResourceId : '');
            return url;
        },

    },

};
