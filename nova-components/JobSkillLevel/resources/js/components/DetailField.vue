<template>
    <panel-item :field="field">
    	<template slot="value">
            <div v-if="field.value && field.value.length" class="multiselect-readonly">
              <div class="">
                <div v-for="(g, required) in groupedValues">
                  <div class="group-label">{{ required == 'true' ? 'обязательные' : 'желательные' }}</div>
                  <ul class="grouped-list">
                    <li v-for="s in g">
                      {{ localizedLabel(s.skill) }} - <b>{{ localizedLabel(s.skill_level) }}</b>
                    </li>
                  </ul>            
                </div>
              </div>
            </div>
            <p v-else>&mdash;</p>            
    	</template>
    </panel-item>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    computed: {

        groupedValues: function () {
            return _.groupBy(this.field.value, 'required');
        },

    },

    methods: {

        localizedLabel: function (obj) {
            return obj.name[document.documentElement.lang];
        },

    },

}
</script>
