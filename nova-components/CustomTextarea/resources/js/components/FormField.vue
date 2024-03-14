<template>
  <default-field :field="field" :errors="errors" :full-width-content="true">
    <template slot="field">
      <excerpt v-if="isReadonly"
        :content="value"
        :plain-text="true"
        class="w-full h-full"
        :should-show="true"
      />
      <div v-else>      
        <textarea
          class="w-full py-3 h-auto custom-textarea"
          :id="field.attribute"
          :dusk="field.attribute"
          v-model="value"
          v-bind="extraAttributes"
        />
      </div>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: {
    resourceName: { type: String },
    field: {
      type: Object,
      required: true,
    },
  },

  computed: {
    defaultAttributes() {
      return {
        rows: this.field.rows,
        class: this.errorClasses,
        placeholder: '',
      }
    },

    extraAttributes() {
      const attrs = this.field.extraAttributes

      return {
        ...this.defaultAttributes,
        ...attrs,
      }
    },
  },
}
</script>
