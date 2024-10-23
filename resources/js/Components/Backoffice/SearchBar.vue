<template>
    <form @submit.prevent="search()" class="flex flex-col gap-1 pb-6 mb-6 border-b border-slate-200 md:flex-row md:items-center">
        <div class="grid w-full grid-cols-1 gap-2 mb-4 md:mb-0 md:grid-cols-3 md:mr-4">
            <!-- name -->
            <Input type="search" id="filter-company-name" v-model.trim="form.name" @input="search()" :placeholder="lang.searchbar['Search by company name']" :error="form.errors.name"/>
            <!-- / -->

            <!-- vat -->
            <Input type="search" id="filter-company-vat" v-model.trim="form.vat" @input="search()" :placeholder="lang.searchbar['Search by VAT/Inc. number']" :error="form.errors.vat"/>
            <!-- / -->

            <!-- address -->
            <Input type="search" id="filter-company-address" v-model.trim="form.address" @input="search()" :placeholder="lang.searchbar['Search by company address']" :error="form.errors.address"/>
            <!-- / -->
        </div>

        <Button icon="fa-solid fa-filter" @click="data.openModal = true" :title="lang.searchbar['More filters']" class="w-full md:w-fit"/>
        <Button type="router" icon="fa-solid fa-arrow-rotate-left" color="gray" :href="route(props.submitRoute)" :title="lang.searchbar['Filter reset']" class="w-full md:w-fit" />

        <!-- modale filtri -->
        <Modal :isOpen="data.openModal" @close="data.openModal = false">
            <template #title>
                <i class="mr-1 fa-solid fa-filter"></i>
                {{ lang.searchbar['More filters'] }}
            </template>

            <template #actions>
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-x-4 gap-y-6 md:grid-cols-3">
                        <!-- company size -->
                        <Select v-model="form.company_size_id" id="filter-company-size" :label="lang.searchbar['Size']" :default="lang.searchbar['Select size']" :defaultOptionDisabled="false" :options="$page.props.picklists.company_sizes" :error="form.errors.company_size_id" />
                        <!-- / -->

                        <!-- business sector -->
                        <Select v-model="form.industry_id" id="filter-company-business-sector" :label="lang.searchbar['Business sector']" :default="lang.searchbar['Select business sector']" :defaultOptionDisabled="false" :options="$page.props.picklists.business_sectors" :error="form.errors.business_sector_id" />
                        <!-- / -->

                        <!-- turnover -->
                        <Select v-model="form.turnover_id" id="filter-company-turnover" :label="lang.searchbar['Turnover']" :default="lang.searchbar['Select turnover']" :defaultOptionDisabled="false" :options="$page.props.picklists.turnovers" :error="form.errors.turnover_id" />
                        <!-- / -->

                        <!-- years of operation -->
                        <Select v-model="form.years_of_operation_id" id="filter-company-years" :label="lang.searchbar['Years of operation']" :default="lang.searchbar['Select years of operation']" :defaultOptionDisabled="false" :options="$page.props.picklists.years_of_operations" :error="form.errors.years_of_operation_id" />
                        <!-- / -->

                        <!-- business interests -->
                        <Listbox id="filter-company-business-interests" :label="lang.searchbar['Business interests']" v-model="form.business_interest_ids" :options="$page.props.picklists.business_interests" :error="form.errors.business_interest_ids" />
                        <!-- / -->

                        <!-- investor -->
                        <fieldset class="p-1 border border-slate-300 rounded-md">
                            <legend class="text-xs font-semibold px-1 mb-0.5">{{ lang.searchbar['Investor'] }}</legend>
                            
                            <div class="flex items-center gap-2">
                                <label for="filter-investor-true" class="text-xs font-semibold px-1 mb-0.5 cursor-pointer flex items-center gap-1">
                                    {{ lang.searchbar['Yes'] }}
                                    <input id="filter-investor-true" type="radio" @change="search()" name="filter-investor" v-model="form.investor" :value="[1]" class="bg-transparent border-2 appearance-none cursor-pointer text-emerald-600 border-emerald-600 focus:ring-1 focus:outline-none focus-visible:outline-none focus:ring-offset-transparent focus:ring-emerald-600/50 focus-visible:ring-emerald-600/50"/>
                                </label>
            
                                <label for="filter-investor-false" class="text-xs font-semibold px-1 mb-0.5 cursor-pointer flex items-center gap-1">
                                    {{ lang.searchbar['No'] }}
                                    <input id="filter-investor-false" type="radio" @change="search()" name="filter-investor" v-model="form.investor" :value="[0]" class="bg-transparent border-2 appearance-none cursor-pointer text-emerald-600 border-emerald-600 focus:ring-1 focus:outline-none focus-visible:outline-none focus:ring-offset-transparent focus:ring-emerald-600/50 focus-visible:ring-emerald-600/50"/>
                                </label>
            
                                <label for="filter-investor-both" class="text-xs font-semibold px-1 mb-0.5 cursor-pointer flex items-center gap-1">
                                    {{ lang.searchbar['Both'] }}
                                    <input id="filter-investor-both" type="radio" @change="search()" name="filter-investor" v-model="form.investor" :value="[1, 0]" class="bg-transparent border-2 appearance-none cursor-pointer text-emerald-600 border-emerald-600 focus:ring-1 focus:outline-none focus-visible:outline-none focus:ring-offset-transparent focus:ring-emerald-600/50 focus-visible:ring-emerald-600/50"/>
                                </label>
                            </div>
                        </fieldset>
                        <!-- / -->

                        <!-- distributor -->
                        <fieldset class="p-1 border border-slate-300 rounded-md">
                            <legend class="text-xs font-semibold px-1 mb-0.5">{{ lang.searchbar['Distributor'] }}</legend>
                            
                            <div class="flex items-center gap-2">
                                <label for="filter-distributor-true" class="text-xs font-semibold px-1 mb-0.5 cursor-pointer flex items-center gap-1">
                                    {{ lang.searchbar['Yes'] }}
                                    <input id="filter-distributor-true" type="radio" @change="search()" name="filter-distributor" v-model="form.distributor" :value="[1]" class="bg-transparent border-2 appearance-none cursor-pointer text-emerald-600 border-emerald-600 focus:ring-1 focus:outline-none focus-visible:outline-none focus:ring-offset-transparent focus:ring-emerald-600/50 focus-visible:ring-emerald-600/50"/>
                                </label>
            
                                <label for="filter-distributor-false" class="text-xs font-semibold px-1 mb-0.5 cursor-pointer flex items-center gap-1">
                                    {{ lang.searchbar['No'] }}
                                    <input id="filter-distributor-false" type="radio" @change="search()" name="filter-distributor" v-model="form.distributor" :value="[0]" class="bg-transparent border-2 appearance-none cursor-pointer text-emerald-600 border-emerald-600 focus:ring-1 focus:outline-none focus-visible:outline-none focus:ring-offset-transparent focus:ring-emerald-600/50 focus-visible:ring-emerald-600/50"/>
                                </label>
            
                                <label for="filter-distributor-both" class="text-xs font-semibold px-1 mb-0.5 cursor-pointer flex items-center gap-1">
                                    {{ lang.searchbar['Both'] }}
                                    <input id="filter-distributor-both" type="radio" @change="search()" name="filter-distributor" v-model="form.distributor" :value="[1, 0]" class="bg-transparent border-2 appearance-none cursor-pointer text-emerald-600 border-emerald-600 focus:ring-1 focus:outline-none focus-visible:outline-none focus:ring-offset-transparent focus:ring-emerald-600/50 focus-visible:ring-emerald-600/50"/>
                                </label>
                            </div>
                        </fieldset>
                        <!-- / -->                        
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                        <!-- countries of operation -->
                        <Listbox id="filter-company-op-country" :label="lang.searchbar['Countries of operation']" v-model="form.op_country_ids" :options="countries" :error="form.errors.op_country_ids" />
                        <!-- / -->
    
                        <!-- Areas you want to do business with -->
                        <Listbox id="filter-company-geoarea" :label="lang.searchbar['Areas you want to do business with']" v-model="form.geoarea_ids" :options="$page.props.picklists.geoareas" :error="form.errors.geoarea_ids" />
                        <!-- / -->
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                        <!-- products tags -->
                        <fieldset class="p-2 border border-slate-200 rounded-md">
                            <legend class="px-1 text-sm font-semibold">{{ lang.searchbar['Products'] }}</legend>
                            <div class="space-y-2">
                                <div>
                                    <label for="product-offered-tags" class="px-1 text-xs font-medium leading-none text-black whitespace-nowrap">
                                        {{ lang.searchbar['Offered products'] }}
                                    </label>
                                    <Multiselect
                                        id="product-offered-tags"
                                        v-model="form.product_tags_offered"
                                        :options="props.all_tags.map(tag => ({value: tag.id, label: tag.name}))"
                                        mode="tags"
                                        :close-on-select="false"
                                        :searchable="true"
                                    />
                                </div>
        
                                <div>
                                    <label for="product-searched-tags" class="px-1 text-xs font-medium leading-none text-black whitespace-nowrap">
                                        {{ lang.searchbar['Searched products'] }}
                                    </label>
                                    <Multiselect
                                        id="product-searched-tags"
                                        v-model="form.product_tags_searched"
                                        :options="props.all_tags.map(tag => ({value: tag.id, label: tag.name}))"
                                        mode="tags"
                                        :close-on-select="false"
                                        :searchable="true"
                                    />
                                </div>
                            </div>
                        </fieldset>
                        <!-- / -->
    
                        <!-- services tag -->
                        <fieldset class="p-2 border border-slate-200 rounded-md">
                            <legend class="px-1 text-sm font-semibold">{{ lang.searchbar['Services'] }}</legend>
                            <div class="space-y-2">
                                <div>
                                    <label for="filter-service-offered-tags" class="px-1 text-xs font-medium leading-none text-black whitespace-nowrap">
                                        {{ lang.searchbar['Offered services'] }}
                                    </label>
                                    <Multiselect
                                        id="service-offered-tags"
                                        v-model="form.service_tags_offered"
                                        :options="props.all_tags.map(tag => ({value: tag.id, label: tag.name}))"
                                        mode="tags"
                                        :close-on-select="false"
                                        :searchable="true"
                                    />
                                </div>
        
                                <div>
                                    <label for="filter-service-searched-tags" class="px-1 text-xs font-medium leading-none text-black whitespace-nowrap">
                                        {{ lang.searchbar['Searched services'] }}
                                    </label>
                                    <Multiselect
                                        id="filter-service-searched-tags"
                                        v-model="form.service_tags_searched"
                                        :options="props.all_tags.map(tag => ({value: tag.id, label: tag.name}))"
                                        mode="tags"
                                        :close-on-select="false"
                                        :searchable="true"
                                    />
                                </div>
                            </div>
                        </fieldset>
                        <!-- / -->
    
                        <!-- technologies tag -->
                        <fieldset class="p-2 border border-slate-200 rounded-md">
                            <legend class="px-1 text-sm font-semibold">{{ lang.searchbar['Technologies'] }}</legend>
                            <div class="space-y-2">
                                <div>
                                    <label for="filter-technology-offered-tags" class="px-1 text-xs font-medium leading-none text-black whitespace-nowrap">
                                        {{ lang.searchbar['Offered technologies'] }}
                                    </label>
                                    <Multiselect
                                        id="filter-technology-offered-tags"
                                        v-model="form.technology_tags_offered"
                                        :options="props.all_tags.map(tag => ({value: tag.id, label: tag.name}))"
                                        mode="tags"
                                        :close-on-select="false"
                                        :searchable="true"
                                    />
                                </div>
        
                                <div>
                                    <label for="filter-technology-searched-tags" class="px-1 text-xs font-medium leading-none text-black whitespace-nowrap">
                                        {{ lang.searchbar['Searched technologies'] }}
                                    </label>
                                    <Multiselect
                                        id="filter-technology-searched-tags"
                                        v-model="form.technology_tags_searched"
                                        :options="props.all_tags.map(tag => ({value: tag.id, label: tag.name}))"
                                        mode="tags"
                                        :close-on-select="false"
                                        :searchable="true"
                                    />
                                </div>
                            </div>
                        </fieldset>
                        <!-- / -->
    
                        <!-- certification tag -->
                        <fieldset class="p-2 border border-slate-200 rounded-md">
                            <legend class="px-1 text-sm font-semibold">{{ lang.searchbar['Certification'] }}</legend>
                            <div>
                                <label for="filter-technology-offered-tags" class="px-1 text-xs font-medium leading-none text-black whitespace-nowrap">
                                    {{ lang.searchbar['Certification'] }}
                                </label>
                                <Multiselect
                                    id="filter-technology-offered-tags"
                                    v-model="form.certification_tags"
                                    :options="props.all_tags.map(tag => ({value: tag.id, label: tag.name}))"
                                    mode="tags"
                                    :close-on-select="false"
                                    :searchable="true"
                                />
                            </div>
                        </fieldset>
                        <!-- / -->
                    </div>

                    <div class="flex flex-col gap-2 pt-6 mt-6 border-t border-slate-200">
                        <!-- applica filtri -->
                        <Button :text="lang.searchbar['Apply filters']" icon="fa-solid fa-filter" @click="search(), data.openModal = false" />
                        <!-- / -->
                    </div>
                </div>
            </template>
        </Modal>
        <!-- / -->
    </form>
</template>

<script setup>
import { reactive, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import Button from '@/Components/Form/Button.vue';
import Input from '@/Components/Form/Input.vue';
import Select from '@/Components/Form/Select.vue';
import Modal from '@/Components/Modal.vue';
import Multiselect from '@vueform/multiselect'
import Listbox from '@/Components/Form/Listbox.vue';
import { omitBy } from 'lodash';

const props = defineProps({
    request: Object,
    submitRoute: String,
    all_tags: Object
});

const lang = usePage().props.localization;

const data = reactive({
    openModal: false,
});

const countries = computed(()=>{
    if(usePage().props.company_country_id === 1){
        return omitBy(usePage().props.picklists.countries, (value, key) => key == 1);
    } else {
        return usePage().props.picklists.countries;
    }
});

const form = useForm({
    name: props.request.name ?? null,
    vat: props.request.vat ?? null,
    address: props.request.address ?? null,
    company_size_id: props.request.company_size_id ?? null,
    years_of_operation_id: props.request.years_of_operation_id ?? null,
    business_sector_id: props.request.business_sector_id ?? null,
    business_interest_ids: props.request.business_interest_ids ?? [],
    turnover_id: props.request.turnover_id ?? null,
    distributor: props.request.distributor ?? [1, 0],
    investor: props.request.investor ?? [1, 0],
    op_country_ids: props.request.op_country_ids ?? [],
    geoarea_ids: props.request.geoarea_ids ?? [],
    perPage: 10,

    product_tags_offered: props.request.product_tags_offered,
    product_tags_searched: props.request.product_tags_searched,
    service_tags_offered: props.request.service_tags_offered,
    service_tags_searched: props.request.service_tags_searched,
    technology_tags_offered: props.request.technology_tags_offered,
    technology_tags_searched: props.request.technology_tags_searched,
    certification_tags: props.request.certification_tags
});

const search = (perPageItems)=>{
    form.perPage = perPageItems;
    
    form.get(route(props.submitRoute), {
        preserveState: true
    });
}

defineExpose({
    search
});

</script>
