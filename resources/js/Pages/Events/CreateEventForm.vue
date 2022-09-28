<template>
    <jet-form-section @submitted="createEvent">
        <template #title>
            Aufgaben Details
        </template>

        <template #description>
            Erstellen Sie eine Aufgabe an der andere Teilnehmen können.
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="name" value="Name" />
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autofocus />
                <jet-input-error :message="form.errors.name" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-2">
                <jet-label for="quota" value="Anzahl Personen" />
                <jet-input id="quota" type="number" min="1" step="0.5" class="mt-1 block w-full" v-model="form.quota" autofocus />
                <jet-input-error :message="form.errors.quota" class="mt-2" />
            </div>
            <div class="col-span-6">
                <input type="file" class="hidden"
                       ref="image"
                       @change="updateImagePreview">
                <jet-label for="image" value="Bild" />

                <!-- New Profile Photo Preview -->
                <div class="mt-2" v-show="imagePreview">
                    <span class="block rounded-full w-20 h-20"
                          :style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + imagePreview + '\');'">
                    </span>
                </div>

                <jet-secondary-button class="mt-2 mr-2" type="button" @click.prevent="selectNewImage">
                    Bild wählen
                </jet-secondary-button>

                <jet-input-error :message="form.errors.image" class="mt-2" />

            </div>
            <div class="col-span-6">
                <jet-label for="event_group_id" value="Gruppe" />
                <Multiselect
                    id="event_group_id"
                    v-model="form.event_group_id"
                    :options="groups"
                    mode="single"
                    value-prop="id"
                    track-by="name"
                    label="name"
                    searchable
                />
                <jet-input-error :message="form.errors.event_group_id" class="mt-2" />
            </div>
            <div class="col-span-3 sm:col-span-2">
                <jet-label for="start_date" value="Beginn" />
                <jet-input id="start_date" type="datetime-local" class="mt-1 block w-full" v-model="form.start_date" autofocus />
                <jet-input-error :message="form.errors.start_date" class="mt-2" />
            </div>
            <div class="col-span-3 sm:col-span-2">
                <jet-label for="end_date" value="Ende" />
                <jet-input id="end_date" type="datetime-local" class="mt-1 block w-full" v-model="form.end_date" autofocus />
                <jet-input-error :message="form.errors.end_date" class="mt-2" />
            </div>
            <div class="col-span-3 sm:col-span-2">
                <jet-label for="approximate_hours" value="Zeitaufwand (Stunden)" />
                <jet-input id="approximate_hours" type="number" step="0.5" min="0.5" class="mt-1 block w-full" v-model="form.approximate_hours" autofocus />
                <jet-input-error :message="form.approximate_hours" class="mt-2" />
            </div>
            <div class="col-span-6">
                <jet-label for="interests" value="Interessen" />
                <Multiselect
                    id="interests"
                    v-model="form.interests"
                    :options="interests"
                    mode="tags"
                    value-prop="id"
                    track-by="name"
                    label="name"
                    searchable
                />
                <jet-input-error :message="form.errors.interests" class="mt-2" />
            </div>
            <div class="col-span-6">
                <jet-label for="description" value="Kurzbeschreibung" />
                <jet-input id="description" type="text" class="mt-1 block w-full" v-model="form.description" autofocus />
                <jet-input-error :message="form.errors.description" class="mt-2" />
            </div>
            <div class="col-span-6">
                <jet-label for="long_description" value="Beschreibung" />
                <jet-text-area id="long_description" min="1" step="0.5" class="mt-1 block w-full" v-model="form.long_description" autofocus />
                <jet-input-error :message="form.errors.long_description" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <jet-button type="link" :href="route('events.index')" class="mr-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
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
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetTextArea from '@/Jetstream/TextArea.vue'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import Multiselect from '@vueform/multiselect'

    export default {
        props: ['interests', 'groups'],

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
                    name: '',
                    start_date: new Date(),
                    end_date: new Date(),
                    interests: [],
                    event_group_id: undefined,
                    quota: 1,
                    approximate_hours: 1,
                    description: '',
                    long_description: '',
                    image: null
                }),

                imagePreview: null,
            }
        },

        methods: {
            createEvent() {
                if (this.$refs.image) {
                    this.form.image = this.$refs.image.files[0]
                }

                this.form.post(route('events.store'), {
                    errorBag: 'createEvent',
                    preserveScroll: true
                });
            },

            selectNewImage() {
                this.$refs.image.click();
            },

            updateImagePreview() {
                const image = this.$refs.image.files[0];

                if (! image) return;

                const reader = new FileReader();

                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };

                reader.readAsDataURL(image);
            },
        },
    }
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
