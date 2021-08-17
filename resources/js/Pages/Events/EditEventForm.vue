<template>
    <jet-form-section @submitted="createEvent">
        <template #title>
            Aufgaben Details
        </template>

        <template #description>
            Bearbeiten Sie eine Aufgabe
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="name" value="Name" />
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autofocus />
                <jet-input-error :message="form.errors.name" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="start_date" value="Beginn" />
                <jet-input id="start_date" type="datetime-local" class="mt-1 block w-full" v-model="form.start_date" autofocus />
                <jet-input-error :message="form.errors.start_date" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="end_date" value="Ende" />
                <jet-input id="end_date" type="datetime-local" class="mt-1 block w-full" v-model="form.end_date" autofocus />
                <jet-input-error :message="form.errors.end_date" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="quota" value="Kontingent" />
                <jet-input id="quota" type="number" min="1" step="0.5" class="mt-1 block w-full" v-model="form.quota" autofocus />
                <jet-input-error :message="form.errors.quota" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="description" value="Kurzbeschreibung" />
                <jet-input id="description" type="text" class="mt-1 block w-full" v-model="form.description" autofocus />
                <jet-input-error :message="form.errors.description" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="long_description" value="Beschreibung" />
                <jet-text-area id="long_description" min="1" step="0.5" class="mt-1 block w-full" v-model="form.long_description" autofocus />
                <jet-input-error :message="form.errors.long_description" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Speichern
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
    import JetButton from '@/Jetstream/Button'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetTextArea from '@/Jetstream/TextArea.vue'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'

    export default {
        components: {
            JetButton,
            JetFormSection,
            JetInput,
            JetTextArea,
            JetInputError,
            JetLabel,
        },

        mounted() {
            console.log('mounted', this.event)
        },

        props: ['event'],

        data() {
            console.log(new Date(this.event.start_date).toISOString())
            return {
                form: this.$inertia.form({
                    name: this.event.name,
                    start_date: new Date(this.event.start_date).toISOString(),
                    end_date: new Date(this.event.end_date).toISOString(),
                    quota: this.event.quota,
                    description: this.event.description,
                    long_description: this.event.long_description,
                })
            }
        },

        methods: {
            createEvent() {
                this.form.post(route('events.store'), {
                    errorBag: 'createEvent',
                    preserveScroll: true
                });
            },
        },
    }
</script>
