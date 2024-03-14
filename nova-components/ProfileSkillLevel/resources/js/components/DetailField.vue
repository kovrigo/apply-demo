<template>
    <panel-item :field="field">
    	<template slot="value">
            <div v-if="field.value && field.value.length" class="multiselect-readonly">
              <div class="">
                  <ul class="grouped-list">
                    <li v-for="s in field.value">
                      {{ localizedLabel(s.skill) }} - <b>{{ localizedLabel(s.skill_level) }}</b>
                    </li>
                  </ul>
              </div>
            </div>
            <p v-else>&mdash;</p>
    	</template>
    </panel-item>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    methods: {

        localizedLabel: function (obj) {
            return obj.name[document.documentElement.lang];
        },

    },

}
</script>
