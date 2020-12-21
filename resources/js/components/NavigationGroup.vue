<template>  
  <li v-show="groupInformation.visible">  
    <h4 
      class="ml-8 mb-4 text-xs uppercase tracking-wide cursor-pointer text-white-50%" 
      @click="toggle" 
    >{{ 
      __(groupInformation.label) 
    }}<span class="p-2" v-if="closed">{{ __('+') }}</span>
      <span class="p-2" v-else="closed">{{ __('_') }}</span>
    </h4> 
    <loading-view
      :loading="loading"
      :dusk="`nova-resource-manager` + groupInformation.label"
    >
      <ul class="list-reset mb-8" v-if="! closed">
        <li 
          v-for="(resource, index) in resourceInformation"
          :key="index"
          class="leading-tight mb-4 ml-8 text-sm" 
          v-show="resource.visible"
        >
        <router-link 
          :to="{
            name: 'index',
            params: {
                resourceName: resource.name
            }
          }" 
          class="text-white text-justify no-underline dim" 
          :dusk="resource.name + `-resource-link`">{{ 
            __(resource.label)
          }}</router-link>
        </li>
      </ul>
    </loading-view>
  </li>   
</template>

<script>  
import { Minimum } from 'laravel-nova'

export default {    
  props: ['groupName', 'resourceName', 'resource', 'active'], 

  data: () => ({
    loading: false, 
    closed: true,
    resources: [],
    navigationsLoaded: false,
  }),

  async created() {
    if(this.active === this.groupInformation.label) {
      this.toggle()
    }     
  },

  methods: { 
    /**
     * Get the resources based on the current page, search, filters, etc.
     */
    async loadNavigationsIfNotLoaded() { 
      if(! this.navigationsLoaded) {
        this.loading = true
        await this.$nextTick(() => {
          return Minimum(
            Nova.request().get('/nova-api/' + this.resourceName, {
              params: this.resourceRequestQueryString,
            }),
            500
          ).then(({ data }) => {
            this.resources = data.resources     
            this.loading = false
            this.navigationsLoaded = true
          }).catch(() => { 
            this.loading = false
          })
        })
      }
    },  

    resourceNavigationInformation(resource) {
      let data = {};

      resource.fields.forEach(field => { 
        data[field.attribute] = field.value 
      }); 

      return data
    },

    toggle() { 
      this.closed = !this.closed
      this.loadNavigationsIfNotLoaded()
    },  
  },

  computed: { 
    groupInformation() {
      return this.resourceNavigationInformation(this.resource)
    },

    resourceInformation() {
      return this.resources.map(resource => this.resourceNavigationInformation(resource))
    }, 

    availableResource() {
      return this.resources.map(resource => this.resourceNavigationInformation(resource))
    }, 

    resourceRequestQueryString() {
      return {
        viaResource: this.groupName,
        viaResourceId: this.groupInformation.id,
        viaRelationship: 'resources'
      }
    }
  }
}
</script> 
