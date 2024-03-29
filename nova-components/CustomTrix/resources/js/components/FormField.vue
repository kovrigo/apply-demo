<template>
  <default-field
    :field="field"
    :errors="errors"
    :full-width-content="true"
    :key="index"
  >
    <template slot="field">
      <div class="trix-form" :class="{ disabled: isReadonly }">
        <trix-editor
          name="trixman"
          ref="theEditor"
          @keydown.stop
          @trix-change="handleChange"
          @trix-initialize="initialize"
          @trix-attachment-add="handleFileAdd"
          @trix-attachment-remove="handleFileRemove"
          @trix-file-accept="handleFileAccept"
          :value="value"
          class="rounded-lg trix-content"
          :class="{ 'border-danger': hasError }"
        />
      </div>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
  mixins: [HandlesValidationErrors, FormField],

  data: () => ({ draftId: uuidv4(), index: 0 }),

  beforeDestroy() {
    this.cleanUp()
  },

  mounted() {
    Nova.$on(this.field.attribute + '-value', value => {
      this.value = value
      this.index++
    })
  },

  methods: {

    initialize() {
      this.$refs.theEditor.editor.insertHTML(this.value)

      if (this.isReadonly) {
        this.$refs.theEditor.setAttribute('contenteditable', false)
      }
    },

    handleFileAccept(e) {
    },

    fill(formData) {
      formData.append(this.field.attribute, this.value || '')
      formData.append(this.field.attribute + 'DraftId', this.draftId)
    },

    /**
     * Initiate an attachement upload
     */
    handleFileAdd({ attachment }) {
      if (attachment.file) {
        this.uploadAttachment(attachment)
      }
    },

    /**
     * Upload an attachment
     */
    uploadAttachment(attachment) {
      const data = new FormData()
      data.append('Content-Type', attachment.file.type)
      data.append('attachment', attachment.file)
      data.append('draftId', this.draftId)

      Nova.request()
        .post(
          `/nova-api/${this.resourceName}/trix-attachment/${this.field.attribute}`,
          data,
          {
            onUploadProgress: function(progressEvent) {
              attachment.setUploadProgress(
                Math.round((progressEvent.loaded * 100) / progressEvent.total)
              )
            },
          }
        )
        .then(({ data: { url } }) => {
          return attachment.setAttributes({
            url: url,
            href: url,
          })
        })
    },

    /**
     * Remove an attachment from the server
     */
    handleFileRemove({ attachment: { attachment } }) {
      Nova.request()
        .delete(
          `/nova-api/${this.resourceName}/trix-attachment/${this.field.attribute}`,
          {
            params: {
              attachmentUrl: attachment.attributes.values.url,
            },
          }
        )
        .then(response => {})
        .catch(error => {})
    },

    /**
     * Purge pending attachments for the draft
     */
    cleanUp() {
      if (this.field.withFiles) {
        Nova.request()
          .delete(
            `/nova-api/${this.resourceName}/trix-attachment/${this.field.attribute}/${this.draftId}`
          )
          .then(response => console.log(response))
          .catch(error => {})
      }
    },
  },

  computed: {
    defaultAttributes() {
      return {
        placeholder: this.field.placeholder || this.field.name,
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

function uuidv4() {
  return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
    (
      c ^
      (crypto.getRandomValues(new Uint8Array(1))[0] & (15 >> (c / 4)))
    ).toString(16)
  )
}
</script>
