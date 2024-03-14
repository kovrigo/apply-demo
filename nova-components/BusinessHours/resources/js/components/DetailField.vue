<template>
    <panel-item :field="field">
    	<template slot="value">
    		<vue-business-hours class="detail-field" 
    			:localization="localization"
    			:days="values">
			</vue-business-hours>
    	</template>
    </panel-item>
</template>

<script>
import VueBusinessHours from 'vue-business-hours'
import Localization from '../mixins/Localization';

export default {
	components: { VueBusinessHours },

	mixins: [Localization],

    props: ['resource', 'resourceName', 'resourceId', 'field'],

	computed: {

		values: function () {
			return JSON.parse(this.field.value);
		},

	},

}
</script>

<style lang="scss">
	.detail-field {
		pointer-events: none;

		.add-hours {
			opacity: 0!important;
		}

		.remove .font-awesome-button {
			opacity: 0!important;	
		}

	}
</style>