<template>
    <div :class="`text-${field.textAlign}`">
        <template v-if="hasValue">
            <div v-if="field.asHtml || field.asMarkdown" v-html="value" class="py-1" :style="{'maxWidth': maxWidth}"></div>
            <div v-else class="py-1" :style="{'maxWidth': maxWidth}">{{ value }}</div>
        </template>
        <p v-else>—</p>
    </div>
</template>

<script>
const md = require('markdown-it')()

export default {
    props: ['resourceName', 'field'],

    computed: {

        /**
         * Determine if the field has a value other than null.
         */
        hasValue() {
            return this.field.value !== null && this.field.value !== '';
        },

        maxWidth() {
            return this.field.maxWidth || '100%';
        },

        value() {
            return this.field.asMarkdown ? md.render(this.field.value || '') : this.field.value;
        }

    },
}
</script>
