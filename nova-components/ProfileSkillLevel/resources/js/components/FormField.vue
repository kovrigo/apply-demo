<template>
    <default-field :field="field" :errors="errors" :full-width-content="true">
        <template slot="field">
            <div class="multiselect-readonly" v-if="isReadonly && field.value.length > 0">
              <div class="">
                  <ul class="grouped-list">
                    <li v-for="s in field.value">
                      {{ localizedLabel(s.skill) }} - <b>{{ localizedLabel(s.skill_level) }}</b>
                    </li>
                  </ul>
              </div>
            </div>
            <div v-if="isReadonly && field.value.length == 0" class="w-full h-full pt-2">
                &mdash;
            </div>
            <div v-if="!isReadonly">
                <vue-draggable tag="div" v-if="loaded"
                    :list="value" group="skills">
                    <div v-for="item in value" :key="item.key" class="flex items-center mb-2" @dragstart="dragStart">
                        <!-- Drag icon -->
                        <img src="/storage/sort.svg" class="cursor-move" v-if="!isReadonly">
                        <!-- Skill -->
                        <div class="inline-block w-1/3 ml-4">
                            <multiselect class="text-80 leading-tight" track-by="id" 
                                v-model="item.skill" :options="field.skills" :allow-empty="false"
                                :custom-label="localizedLabel" :disabled="isReadonly">
                            </multiselect>
                        </div>
                        <!-- Level -->
                        <div class="inline-block w-1/4 ml-4">
                            <multiselect class="text-80 leading-tight" track-by="id" 
                                v-model="item.skill_level" :options="field.levels" :allow-empty="false"
                                :custom-label="localizedLabel" :disabled="isReadonly">
                            </multiselect>
                        </div>
                        <!-- Delete button -->
                        <a @click.prevent="deleteSkill(item)" href="" 
                            class="ml-4" v-if="!isReadonly">
                            <img src="/storage/remove.svg">
                        </a>
                    </div>
                </vue-draggable>
                <!-- Add skill button -->
                <div class="cursor-pointer" :class="value.length == 0 ? '' : 'add-skill-button'" @click.prevent="addSkill" slot="footer">
                    + добавить навык
                </div>
            </div>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'
import VueDraggable from 'vuedraggable'
import randomIdGenerator from 'random-id-generator'
import Multiselect from 'vue-multiselect'

export default {
    components: { Multiselect, VueDraggable },

    mixins: [FormField, HandlesValidationErrors],

    data: function () {
        return {
            loaded: false,
        }
    },

    props: ['resourceName', 'resourceId', 'field'],

    methods: {

        dragStart: function (e, e2) {
            var img = document.createElement("img");
            img.src = "/storage/che.svg";
            img.width = 0;
            img.height = 0;
            e.dataTransfer.setDragImage(img, 0, 0);
        },

        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            var self = this;
            _.forEach(this.field.value, function (item) {
                self.$set(item, 'key', randomIdGenerator());
            });
            this.value = this.field.value;
            // Loaded
            this.loaded = true;
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
            this.value = value;
        },

        localizedLabel: function (obj) {
            return obj.name[document.documentElement.lang];
        },

        addSkill: function () {
            this.value.push({
                key: randomIdGenerator(),
                skill: _.first(this.field.skills),
                skill_level: _.first(this.field.levels),
                required: false,
            });
        },

        deleteSkill: function (deletingSkill) {
            this.value = this.value.filter(function (skill) {
                return skill.key != deletingSkill.key;
            });
        },

    },
}
</script>

<style type="text/css">
    .add-skill-button {
        margin-top: 30px; 
    }
</style>