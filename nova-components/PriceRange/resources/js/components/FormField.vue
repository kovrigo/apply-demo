<template>
    <default-field :field="field" :errors="errors" :full-width-content="true">
        <template slot="field">
            <div v-if="isReadonly" class="w-full h-full pt-2">
                {{ readonlyValue }}
            </div>
            <div v-else>
                <!-- Exact amount selector -->
                <div class="w-full mb-4">
                    <a class="inline-block cursor-pointer mr-4 animate-text-color select-none mini-tab"
                        :class="{ 'active': value.exact_amount }"
                        @click="toggleExactAmount">
                        точная
                    </a>
                    <a class="inline-block cursor-pointer animate-text-color select-none mini-tab"
                        :class="{ 'active': !value.exact_amount }"
                        @click="toggleExactAmount">
                        приблизительная
                    </a>
                </div>
                <div v-if="value.exact_amount" class="mini-tab-content flex items-end">
                    <!-- Amount -->
                    <input v-if="value.exact_amount" type="number" min="0" placeholder="" class="custom-input price-input" 
                        v-model="value.amount">
                    <!-- Currency -->
                    <multiselect class="text-80 leading-tight ml-4 currency-selector" track-by="id" label="name" 
                        v-model="selectedCurrency" :options="field.currencies" :allow-empty="false">
                    </multiselect>
                </div>
                <div v-else class="mini-tab-content flex items-end">
                    <!-- Min -->
                    <span v-if="!value.exact_amount" class="label">от</span>
                    <input v-if="!value.exact_amount" type="number" min="0" placeholder="" class="custom-input ml-2 price-input" 
                        v-model="value.min" :disabled="isReadonly">
                    <span v-if="!value.exact_amount" class="separator mx-4">—</span>
                    <!-- Max -->
                    <span v-if="!value.exact_amount" class="label">до</span>                
                    <input v-if="!value.exact_amount" type="number" min="0" placeholder="" class="custom-input ml-2 price-input" 
                        v-model="value.max" :disabled="isReadonly">                    
                    <!-- Currency -->
                    <multiselect class="ml-4 currency-selector" track-by="id" label="name" v-model="selectedCurrency" 
                        :options="field.currencies" :allow-empty="false">
                    </multiselect>
                </div>
            </div>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import Multiselect from 'vue-multiselect'

export default {
    components: { Multiselect },

    mixins: [FormField, HandlesValidationErrors],

    data: function () {
        return {
            selectedCurrency: null,
        }
    },

    props: ['resourceName', 'resourceId', 'field'],

    computed: {

        readonlyValue: function () {
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
                return this.field.value.min + ' - ' + this.field.value.max + ' ' + selectedCurrency.name;
            }
            return '—';
        },

    },

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.selectedCurrency = _.find(this.field.currencies, ['id', this.field.value.currency]);
            this.value = this.field.value;
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            this.value.currency = this.selectedCurrency.id;
            this.value.min = this.value.min == '' ? null : this.value.min;
            this.value.max = this.value.max == '' ? null : this.value.max;
            this.value.amount = this.value.amount == '' ? null : this.value.amount;
            formData.append(this.field.attribute, JSON.stringify(this.value));
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value;
        },

        toggleExactAmount: function () {
            this.value.exact_amount = !this.value.exact_amount;
        },

    },
}
</script>

<style type="text/css">
    .currency-selector {
        display: inline-block; 
        width: 60px;
    }

    .price-input {
        width: 80px;
    }

</style>