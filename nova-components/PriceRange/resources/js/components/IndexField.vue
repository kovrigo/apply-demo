<template>
    <div :class="`text-${field.textAlign}`">
        <span v-html="fieldValue"></span>
    </div>
</template>

<script>
export default {
    props: ['resourceName', 'field'],

    computed: {

    	fieldValue: function () {
    		let selectedCurrency = _.find(this.field.currencies, ['id', this.field.value.currency]);
    		if (this.field.value.exact_amount) {
    			if (this.field.value.amount == null) {
    				return '—';
    			}
    			return this.field.value.amount + '&nbsp;' + selectedCurrency.name;
    		}
    		if (this.field.value.min == null && this.field.value.max != null) {
    			return 'до&nbsp;' + this.field.value.max + '&nbsp;' + selectedCurrency.name;	
    		}
    		if (this.field.value.min != null && this.field.value.max == null) {
    			return 'от&nbsp;' + this.field.value.min + '&nbsp;' + selectedCurrency.name;	
    		}
    		if (this.field.value.min != null && this.field.value.max != null) {
    			return this.field.value.min + '&nbsp;—&nbsp;' + this.field.value.max + '&nbsp;' + selectedCurrency.name;
    		}
    		return '—';
    	},

    },
    
}
</script>
