<template>
    <jet-form-section @submitted="createParticipation">
        <template #title>
            Zeiterfassung Details
        </template>

        <template #description>
            Geben Sie die Details zu den verbrachten Elternstunden ein.
        </template>

        <template #form>
            <div class="col-span-6">
                <jet-label for="event_id" value="Aufgabe" />
                <Multiselect
                    id="event_id"
                    v-model="form.event_id"
                    :options="events"
                    mode="single"
                    value-prop="id"
                    track-by="name"
                    label="name"
                    searchable
                />
                <jet-input-error :message="form.errors.event_id" class="mt-2" />
            </div>
            <div class="col-span-6" v-if="$page.props.user.admin">
                <jet-label for="user_id" value="Benutzer" />
                <Multiselect
                    id="user_id"
                    v-model="form.user_id"
                    :options="users"
                    mode="single"
                    value-prop="id"
                    track-by="name"
                    label="name"
                    searchable
                />
                <jet-input-error :message="form.errors.user_id" class="mt-2" />
            </div>
            <input v-else type="hidden" v-model="form.user_id" />
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="minutes" value="Zeit (Minuten)" />
                <jet-input id="minutes" type="number" min="1" step="1" class="mt-1 block w-full" v-model="form.minutes" autofocus />
                <jet-input-error :message="form.errors.minutes" class="mt-2" />
            </div>
            <div class="col-span-3 sm:col-span-2">
                <jet-label for="participation_date" value="Datum" />
                <jet-input id="participation_date" type="date" class="mt-1 block w-full" v-model="form.participation_date" autofocus />
                <jet-input-error :message="form.errors.participation_date" class="mt-2" />
            </div>
            <div class="col-span-6">
                <jet-label for="description" value="Beschreibung" />
                <jet-input id="description" type="text" class="mt-1 block w-full" v-model="form.description" autofocus />
                <jet-input-error :message="form.errors.description" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <jet-button type="link" :href="route('participations.index')" class="mr-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Abbrechen
            </jet-button>
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
    import JetSecondaryButton from "@/Jetstream/SecondaryButton";
    import Multiselect from '@vueform/multiselect'

    export default {
        props: ['events', 'users'],

        components: {
            JetButton,
            JetSecondaryButton,
            JetFormSection,
            JetInput,
            JetTextArea,
            JetInputError,
            JetLabel,
            Multiselect
        },

        data() {
            return {
                form: this.$inertia.form({
                    participation_date: new Date(),
                    event_id: undefined,
                    user_id: this.$page.props.user.id,
                    description: '',
                    minutes: 1,
                })
            }
        },

        methods: {
            createParticipation() {
                this.form.post(route('participations.store'), {
                    errorBag: 'createParticipation',
                    preserveScroll: true
                });
            },
        },
    }
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
