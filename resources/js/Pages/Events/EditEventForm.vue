<template>
    <jet-form-section @submitted="updateEvent">
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

                <!-- Current Profile Photo -->
                <div class="mt-2" v-show="! imagePreview">
                    <img :src="event.image_url" :alt="event.name" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" v-show="imagePreview">
                    <span class="block rounded-full w-20 h-20"
                          :style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + imagePreview + '\');'">
                    </span>
                </div>

                <jet-secondary-button class="mt-2 mr-2" type="button" @click.prevent="selectNewImage">
                    Bild ändern
                </jet-secondary-button>

                <jet-secondary-button type="button" class="mt-2" @click.prevent="deleteImage" v-if="event.image">
                    Bild entfernen
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
            <div class="col-span-6 flex flex-col">
                <jet-label for="long_description" value="Beschreibung" />
                <quill-editor toolbar="minimal" v-model:content="form.long_description" content-type="html"/>
                <!-- <jet-text-area id="long_description" min="1" step="0.5" class="mt-1 block w-full" v-model="form.long_description" autofocus /> -->
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
    import dayjs from "dayjs"
    import Multiselect from '@vueform/multiselect'
    import { QuillEditor } from '@vueup/vue-quill'
    import '@vueup/vue-quill/dist/vue-quill.snow.css';

    export default {
        components: {
            QuillEditor,
            JetButton,
            JetFormSection,
            JetInput,
            JetTextArea,
            JetInputError,
            JetLabel,
            JetSecondaryButton,
            Multiselect
        },

        props: ['event', 'interests', 'groups'],

        data() {
            return {
                form: this.$inertia.form(Object.assign({}, this.event, {
                    _method: 'PUT',
                    start_date: dayjs(this.event.start_date).format('YYYY-MM-DDThh:mm'),
                    end_date: dayjs(this.event.end_date).format('YYYY-MM-DDThh:mm'),
                    interests: this.event.interests?.map(i => i.id),
                    event_group_id: this.event.event_group_id,
                    image: null
                })),

                imagePreview: null,
            }
        },

        methods: {
            updateEvent() {
                if (this.$refs.image) {
                    this.form.image = this.$refs.image.files[0]
                }

                this.form.post(route('events.update', {id: this.event.id}), {
                    errorBag: 'updatedEvent',
                    preserveScroll: true,
                    onSuccess: () => (this.clearImageFileInput()),
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

            deleteImage() {
                this.$inertia.delete(route('events.destroyimage', this.event.id), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.imagePreview = null;
                        this.clearImageFileInput();
                    },
                });
            },

            clearImageFileInput() {
                if (this.$refs.image?.value) {
                    this.$refs.image.value = null;
                }
            },
        },
    }
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
