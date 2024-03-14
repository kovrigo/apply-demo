<template>  
  <panel-item :field="field">
    <template slot="value">
      <span class="lowercase">{{ label }}</span>
    </template>
  </panel-item>    
</template>

<script>
export default {
  props: ['resource', 'resourceName', 'resourceId', 'field'],

  computed: {
    label() {
      return this.field.value == true ? this.__('Yes') : this.__('No')
    },
  },
}
</script>
