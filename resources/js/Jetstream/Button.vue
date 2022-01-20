<template>
    <component :href="href" :is="tag" :type="type" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" @click="click($event)">
        <slot></slot>
    </component>
</template>

<script>
    export default {
        props: {
            type: {
                type: String,
                default: 'submit',
            },
            href: {
                type: String,
                default: undefined,
            },
            post: {
                type: Boolean,
                default: false,
            },
        },
        computed: {
            tag() {
                return this.type === 'link' ? 'inertia-link' : 'button'
            }
        },
        methods: {
            click (event) {
                if (this.post) {
                    this.$inertia.post(this.href)
                    event.preventDefault();
                }
            }
        }
    }
</script>
