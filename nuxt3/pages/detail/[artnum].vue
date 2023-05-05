<script setup>

const route = useRoute()

const response = await $fetch(
    `http://localhost:8108/collections/products/documents/` + route.params.artnum,
    {
        method: "GET",
        headers: {
            'X-TYPESENSE-API-KEY': 'xyz'
        }
    }
).then((response) => {

    if (response && typeof response === 'object' && response.hasOwnProperty('artNum')) {
        return response;
    }

    return []
}).catch((error) => {
    return []
});

console.log(response);

</script>

<template>

<!--  <p>artNum: {{response.artNum}}</p>-->
<!--  <p>articleName: {{response.articleName}}</p>-->
<!--  <p>brand: {{response.brand}}</p>-->
<!--  <p>color: {{response.color}}</p>-->
<!--  <p>id: {{response.id}}</p>-->
<!--  <p>material: {{response.material}}</p>-->
<!--  <p>recommendations: {{response.recommendations}}</p>-->
<!--  <p>shortName: {{response.shortName}}</p>-->
<!--  <p>url: {{response.url}}</p>-->



    <div class="bg-white">
        <div class="mx-auto px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            <!-- Product -->
            <div class="lg:grid lg:grid-cols-7 lg:grid-rows-1 lg:gap-x-8 lg:gap-y-10 xl:gap-x-16">
                <!-- Product image -->
                <div class="lg:col-span-4 lg:row-end-1">
                    <div class="aspect-h-3 aspect-w-4 overflow-hidden rounded-lg bg-gray-100">
                        <img :src="response.url" class="object-cover object-center">
                    </div>
                </div>

                <!-- Product details -->
                <div class="mx-auto mt-14 max-w-2xl sm:mt-16 lg:col-span-3 lg:row-span-2 lg:row-end-2 lg:mt-0 lg:max-w-none">
                    <div class="flex flex-col-reverse">
                        <div class="mt-4">
                            <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">{{response.articleName}}</h1>

                            <h2 id="information-heading" class="sr-only">Product information</h2>
                        </div>
                    </div>

                    <p class="mt-6 text-gray-400">{{response.artNum}}</p>
                    <p class="text-gray-500">{{response.shortName}}</p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                        <button type="button" class="flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50">Pay</button>
                    </div>

                    <div class="mt-10 border-t border-gray-200 pt-10">
                        <div class="prose prose-sm mt-4 text-gray-500">
                            <ul role="list">
                                <li>Color: {{response.color}}</li>
                                <li>Material: {{response.material}}</li>
                            </ul>
                        </div>
                    </div>

                </div>


            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
