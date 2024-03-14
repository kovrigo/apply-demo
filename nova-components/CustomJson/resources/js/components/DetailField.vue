<template>
  <panel-item :field="field">
    <template slot="value">
      <vue-json-editor
        v-model="field.value"
        :show-btns="false"
        :mode="'view'"
        :expandedOnStart="true"
      ></vue-json-editor>
    </template>
  </panel-item>
</template>

<script>
import vueJsonEditor from "vue-json-editor";

export default {
  props: ["resource", "resourceName", "resourceId", "field"],
  components: {
    vueJsonEditor,
  },
};
</script>
<style src="../../css/custom.css"></style>

