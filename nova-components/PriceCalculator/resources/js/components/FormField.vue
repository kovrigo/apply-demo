<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <div class="flex items-end">
                <input type="number" min="0" step="1" placeholder="" class="custom-input ml-2 price-input" 
                    @input="handleCountChange" ref="count">
                <span class="label mx-3">x {{ unitPrice }}</span>
                <span class="label mr-3">=</span>
                <input type="number" min="0" step="0.01" placeholder="" class="custom-input ml-2 price-input" 
                    @input="handleAmountChange" ref="amount">
                <span class="label">{{ field.currency.name }}</span>
            </div>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
    mixins: [FormField, HandlesValidationErrors],

    data: function () {
        return {
            count: 0,
        }
    },

    props: ['resourceName', 'resourceId', 'field'],

    computed: {

        unitPrice: function () {
            return _.round(this.field.unitPrice, 2);
        },

    },

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || 0
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(this.field.attribute, this.value || 0)
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value
        },

        handleCountChange(e) {
            this.count = _.toNumber(e.target.value);
            this.value = _.round(this.count * this.field.unitPrice, 2);
            this.$refs.amount.value = this.value;
        },

        handleAmountChange(e) {            
            this.value = _.toNumber(e.target.value);    
            this.count = _.round(this.value / this.field.unitPrice);
            this.$refs.count.value = this.count;            
        },

    },
}
</script>
