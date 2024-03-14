<template>
  <default-field :field="field" :errors="errors">
    <template slot="field">
      <div v-if="loaded">
        <div v-if="isReadonly" class="w-full pt-2">
          <span v-if="selected">{{ selected.label }}</span>
          <span v-else>&mdash;</span>
        </div>
        <multiselect v-else class="text-80 pb-2 leading-tight" track-by="value" label="label" 
          v-model="selected" :options="field.options" :dusk="field.attribute">
        </multiselect>
      </div>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import Multiselect from 'vue-multiselect'

export default {
  components: { Multiselect },

  mixins: [HandlesValidationErrors, FormField],

  data () {
      return {
          selected: null,
          loaded: false,
      };    
  },

  methods: {
    /**
     * Provide a function that fills a passed FormData object with the
     * field's internal value attribute. Here we are forcing there to be a
     * value sent to the server instead of the default behavior of
     * `this.value || ''` to avoid loose-comparison issues if the keys
     * are truthy or falsey
     */
    fill(formData) {
      formData.append(this.field.attribute, JSON.stringify(this.selected ? this.selected.value : null));
    },
  },


  mounted: function () {
    this.selected = _.find(this.field.options, ['value', this.value]);
    this.loaded = true;
  },

  computed: {
    /**
     * Return the placeholder text for the field.
     */
    placeholder() {
      return this.field.placeholder || this.__('Choose an option')
    },

  },
}
</script>
