<template>
    <panel-item :field="field">
    	<template slot="value">
    		{{ fieldValue }}
    	</template>
    </panel-item>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    computed: {

    	fieldValue: function () {
    		let selectedCurrency = _.find(this.field.currencies, ['id', this.field.value.currency]);
    		if (this.field.value.exact_amount) {
    			if (this.field.value.amount == null) {
    				return '—';
    			}
    			return this.field.value.amount + ' ' + selectedCurrency.name;
    		}
    		if (this.field.value.min == null && this.field.value.max != null) {
    			return 'до ' + this.field.value.max + ' ' + selectedCurrency.name;	
    		}
    		if (this.field.value.min != null && this.field.value.max == null) {
    			return 'от ' + this.field.value.min + ' ' + selectedCurrency.name;	
    		}
    		if (this.field.value.min != null && this.field.value.max != null) {
    			return this.field.value.min + ' — ' + this.field.value.max + ' ' + selectedCurrency.name;
    		}
    		return '—';
    	},

    },

}
</script>
