<template>
  <default-field :field="field" :errors="errors">
    <template slot="field">
      <div v-if="isReadonly" class="w-full h-full pt-2">
        {{ value }}
      </div>
      <div v-else class="h-full flex items-end">
        <input
          class="custom-input w-full"
          :id="field.attribute"
          :dusk="field.attribute"
          v-model="value"
          v-bind="extraAttributes"
          :disabled="isReadonly"
          :list="`${field.attribute}-list`"
        />

        <datalist
          v-if="field.suggestions && field.suggestions.length > 0"
          :id="`${field.attribute}-list`"
        >
          <option v-for="suggestion in field.suggestions" :value="suggestion" />
        </datalist>
      </div>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
  mixins: [HandlesValidationErrors, FormField],

  computed: {
    defaultAttributes() {
      return {
        type: this.field.type || 'text',
        min: this.field.min,
        max: this.field.max,
        step: this.field.step,
        pattern: this.field.pattern,
        placeholder: '',
        class: this.errorClasses,
      }
    },

    extraAttributes() {
      const attrs = this.field.extraAttributes

      return {
        // Leave the default attributes even though we can now specify
        // whatever attributes we like because the old number field still
        // uses the old field attributes
        ...this.defaultAttributes,
        ...attrs,
      }
    },
  },
}
</script>
