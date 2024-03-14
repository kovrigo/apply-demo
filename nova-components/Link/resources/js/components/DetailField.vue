<template>
  <panel-item :field="field">
    <template slot="value">
      <a :href="href" :target="field.target" class="no-underline dim text-primary font-bold router-link-exact-active router-link-active text-left text-left">
      	{{ field.text }}
      </a>
    </template>
  </panel-item>
</template>

<script>
import href from '../mixins/href';

export default {
	mixins: [href],

    props: ['resource', 'resourceName', 'resourceId', 'field'],

}
</script>
