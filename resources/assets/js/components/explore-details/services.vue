<template>
	<section id="servicios" class="section">
    <div class="container">
			<h3 class="subtitle is-size-5 has-text-weight-bold" style="margin-bottom: 0;">
				Servicios
			</h3>
			<p>
				Tienes estos servicios a tu disposición
			</p>

			<div class="columns is-multiline">
				<div 
					v-for="(s,i) in services"
					:key="i"
					class="column is-2"
					:class="{ 'is-hidden-mobile': (i > (limitMobile-1) && !showMore), 'is-hidden-tablet': (i > (limitDesktop-1) && !showMore) }"
					>
					<img 
						v-if="s.image"
						:src="s.image" :alt="s.name" />
					<h6 class="is-size-7 has-text-weight-normal">
						{{ s.name }}
					</h6>
				</div>
			</div>

			<button
				class="button is-outlined is-primary is-small"
				@click="showMore = true"
				>
				Mostrar todos los servicios
			</button>			
		</div>

		<div v-if="showMore" class="container">
			<h3 class="subtitle is-size-5 has-text-weight-bold" style="margin-bottom: 0;">
				Servicios de condominio
			</h3>
			<div class="columns is-multiline">
				<div
					v-if="servicesCommun.length > 0" 
					v-for="(s,i) in servicesCommun"
					:key="i"
					class="column is-2"
					>
					<img 
						v-if="s.image"
						:src="'/images/'+s.image" :alt="s.name" />
					<h6 class="is-size-7 has-text-weight-normal">
						{{ s.name }}
					</h6>
				</div>
				<div v-else class="column is-12">
					Esta propiedad no posee ninguno de estos servicios.
				</div>
			</div>
		</div>
		<div v-if="showMore" class="container">
			<h3 class="subtitle is-size-5 has-text-weight-bold" style="margin-bottom: 0;">
				Servicios básicos
			</h3>
			<div class="columns is-multiline">
				<div
					v-if="servicesBasic.length > 0"  
					v-for="(s,i) in servicesBasic"
					:key="i"
					class="column is-2"
					>
					<img 
						v-if="s.image"
						:src="'/images/'+s.image" :alt="s.name" />
					<h6 class="is-size-7 has-text-weight-normal">
						{{ s.name }}
					</h6>
				</div>
				<div v-else class="column is-12">
					Esta propiedad no posee ninguno de estos servicios.
				</div>
			</div>
		</div>
	</section>
</template>

<script>

export default {
  props: ['services', 'services-commun', 'services-basic'],
  data: function () {
    return {
    	showMore: false,
      limitMobile: 4,
      limitDesktop: 12
    }
  },
  mounted() {
  	console.log(this.services.length)
  },
  methods: {

  },
  computed: {  	
    
  }
}
</script>

<style scoped>
	.columns {
		margin-top: 1rem;
	}
	.has-background-image {
		background-size: cover;
		background-position: center;
	}
</style>