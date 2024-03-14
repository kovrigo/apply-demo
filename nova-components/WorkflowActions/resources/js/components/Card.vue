<template>
    <loading-card :loading="loading" class="flex flex-col items-start justify-start workflow-card">
        <div class="w-full">
            <div v-if="!loading">
                <div class="top-wrapper flex items-center justify-between mb-1">
                    <div class="state">
                        {{ log.state }}
                    </div>
                    <div class="date">
                        {{ log.date }}
                    </div>
                </div>
                <div class="user mb-1">
                    {{ log.user }}
                </div>
                <div class="notes">
                    {{ log.notes }}
                </div>
            </div>
            <div>
                <button class="btn btn-default cursor-pointer inline-block mr-3 mt-3 transition" 
                    v-for="(action, index) in actions" @click="runAction(action)">
                    {{ action.name }}
                </button>
            </div>
        </div>

        <portal to="modals" transition="fade-transition">
            <component v-if="confirmActionModalOpened"
                       class="text-left"
                       :is="selectedAction.component"
                       :working="working"
                       :selected-resources="selectedResources"
                       :resource-name="resourceName"
                       :action="selectedAction"
                       :errors="errors"
                       @confirm="executeAction"
                       @close="closeConfirmationModal"/>
        </portal>
    </loading-card>
</template>

<script>

import { mapProps } from 'laravel-nova'
import HandlesActions from '~~nova~~/mixins/HandlesActions'

export default {
    mixins: [ HandlesActions ],

    data() {
        return {
            selectedActionKey: null,
            message: null,
            actionMessages: [],
            loading: true,
            workflowActions: [],
            log: null,
        }
    },

    props: {
        card: { type: Object },
        ...mapProps([
            'resourceName',
            'viaResource',
            'viaResourceId',
            'viaRelationship',
            'resource',
            'resourceId',                
        ])
    },

    mounted() {
        this.load();
    },

    computed: {

        actions() {
            return this.workflowActions;
        },

        selectedResources() {
            return this.resourceId;
        }

    },

    watch: {
        resourceId: function(newResourceId, oldResourceId) {
            if (newResourceId != oldResourceId) {
                console.log('new res ID = ' + newResourceId);
                this.loading = true;
                this.load();
            }
        },
    },

    methods: {

        load: function () {
            var self = this;
            return Nova.request().get('/nova-vendor/workflow-actions/' + 
                this.resourceName + '/actions?resourceId=' + this.resourceId).then((res) => {
                    self.workflowActions = res.data.actions;
                    self.log = res.data.log;
                    self.selectedActionKey = self.workflowActions[0] ? self.workflowActions[0].uriKey : null;
                    self.loading = false;
            });
        },

        runAction: function (action) {
            this.selectedActionKey = action.uriKey;
            this.determineActionStrategy();
        }

    },

}
</script>
