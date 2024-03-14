<template>
    <default-field :field="field" :errors="errors" :full-width-content="true">
        <template slot="field">
            <vue-business-hours 
                :localization="localization"
                :days="value">
            </vue-business-hours>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import VueBusinessHours from 'vue-business-hours'
import Localization from '../mixins/Localization';

export default {
    components: { VueBusinessHours },

    mixins: [FormField, HandlesValidationErrors, Localization],

    props: ['resourceName', 'resourceId', 'field'],

    data: function () {
        return {
            daysDefault: 
                {
                  sunday: [
                    {
                      open: '',
                      close: '',
                      id: _.uniqueId(),
                      isOpen: false
                    }
                  ],
                  monday: [
                    {
                      open: '',
                      close: '',
                      id: _.uniqueId(),
                      isOpen: false
                    }
                  ],
                  tuesday: [
                    {
                      open: '',
                      close: '',
                      id: _.uniqueId(),
                      isOpen: false
                    }
                  ],
                  wednesday: [
                    {
                      open: '',
                      close: '',
                      id: _.uniqueId(),
                      isOpen: false
                    }
                  ],
                  thursday: [
                    {
                      open: '',
                      close: '',
                      id: _.uniqueId(),
                      isOpen: false
                    }
                  ],
                  friday: [
                    {
                      open: '',
                      close: '',
                      id: _.uniqueId(),
                      isOpen: false
                    },
                  ],
                  saturday: [
                    {
                      open: '',
                      close: '',
                      id: _.uniqueId(),
                      isOpen: false
                    }
                  ]
                },
        }
    },

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = JSON.parse(this.field.value) || this.daysDefault;
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(this.field.attribute, JSON.stringify(this.value));
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value
        },
    },
}
</script>
