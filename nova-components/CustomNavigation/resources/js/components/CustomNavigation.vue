<template>
    <div v-if="loaded" class="navigation">
        <div v-for="group in navigation" class="navigation-group">
            <div class="group-label cursor-pointer" @click="toggle(group)">
                {{ group.label }}
                <span v-if="group.expanded">:</span>
            </div>
            <div class="navigation-link" v-for="link in group.links" :class="getLinkClass(link)"
                v-show="group.expanded">
                <a :href="link.url" v-if="!isLinkRoutable(link)">
                    {{ getLinkLabel(link) }}&nbsp;{{ getLinkCount(link) }}
                </a>
                <router-link v-else :to="getLinkRoutableObject(link)">
                    {{ getLinkLabel(link) }}&nbsp;{{ getLinkCount(link) }}
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            navigation: [],
            loaded: false,
            selectedLink: null,
        };    
    },

    props: ["navigationJson"],

    mounted: function () {
        var self = this;
        this.navigation = JSON.parse(this.navigationJson);
        _.forEach(this.navigation, function (group) {
            let expanded = localStorage.getItem(group.label);
            if (expanded === null) {
                expanded = true;
            } else {
                expanded = expanded == 'true';
            }         
            self.$set(group, 'expanded', expanded);
        });
        this.setSelectedLink();
        this.loaded = true;
    },

    methods: {

        toggle: function (group) {
            group.expanded = !group.expanded;
            localStorage.setItem(group.label, group.expanded);    
            let expanded = localStorage.getItem(group.label);   
        },

        setSelectedLink: function () {
            var self = this;
            _.forEach(this.navigation, function (group) {
                _.forEach(group.links, function (link) {
                    if (_.startsWith(window.location.href, link.url)) {
                        if (self.selectedLink) {
                            if (self.selectedLink.url.length < link.url.length) {
                                self.selectedLink = link;
                            }
                        } else {
                            self.selectedLink = link;
                        }
                    }
                });
            });
        },

        getLinkClass: function (link) {
            if (this.selectedLink) {
                return link.url == this.selectedLink.url ? 'selected' : '';    
            }
            return '';
        },

        selectLink: function (link) {
            this.selectedLink = link;
        },

        isLinkRoutable: function (link) {
            return link.url.indexOf('apply\/resources') !== -1;
        },

        isLinkLens: function (link) {
            return link.url.indexOf('\/lens\/') !== -1;
        },

        getResource: function (link) {
            let parts = link.url.split('/');
            let i = _.findIndex(parts, function (part) { return part == 'resources'; });
            return parts[i + 1];
        },

        getLens: function (link) {
            let parts = link.url.split('/');
            let i = _.findIndex(parts, function (part) { return part == 'resources'; });
            return parts[i + 3];
        },

        getLinkRoutableObject: function (link) {
            let isLens = this.isLinkLens(link);
            let res = {
                name: isLens ? 'lens' : 'index',
                params: {
                    resourceName: this.getResource(link),
                },
            };
            if (isLens) {
                res.params.lens = this.getLens(link);
            }
            return res;
        },

        getLinkLabel: function (link) {
            return link.label;
        },

        getLinkCount: function (link) {
            return (link.count !== null ? '(' + link.count + ')' : '');
        },

    },

}
</script>

<style>
/* Scoped Styles */
</style>
