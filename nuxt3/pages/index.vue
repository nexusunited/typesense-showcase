<script>

const response = await $fetch(
    `http://localhost:8108/collections/products/documents/search?q=*&page=1&per_page=1&facet_by=categoryLevel0`,
    {
        method: "GET",
        headers: {
            'X-TYPESENSE-API-KEY': 'xyz'
        }
    }
).then((response) => {

    if (response && typeof response === 'object' && response.hasOwnProperty('facet_counts')) {
        return response;
    }

    return []
}).catch((error) => {
    return []
});

let categories = response['facet_counts'][0]['counts']

console.log(categories);

export default {
    data() {
        return {
            categories: categories
        }
    },
}
</script>
<template>

    <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
        <NuxtLink v-for="(categorie, index) in categories" :to="'/categorie/' + categorie.value" class="group">
            <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-lg bg-gray-200 xl:aspect-h-8 xl:aspect-w-7">
                <img :src="`https://picsum.photos/400/400?random=${index}`" class="h-full w-full object-cover object-center group-hover:opacity-75" />
            </div>
            <h3 class="mt-4 text-sm text-gray-700">{{ categorie.value }} <span class="text-gray-400">({{  categorie.count }})</span></h3>
        </NuxtLink>

    </div>
</template>
