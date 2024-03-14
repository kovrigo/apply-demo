<template>
  <div :class="`text-${field.textAlign}`">
  	<span class="lowercase">{{ label }}</span>
  </div>
</template>

<script>
export default {
  props: ['resourceName', 'field'],

  computed: {

    label: function () {
      return this.field.value == true ? this.__('Yes') : this.__('No');
    },
    
  },

}
</script>
