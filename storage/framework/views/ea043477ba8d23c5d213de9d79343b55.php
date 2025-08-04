<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto p-6 bg-white shadow-md rounded-lg mt-6"
     x-data="formBuilder(<?php echo json_encode($form ?? null, 15, 512) ?>)"
     x-init="init()">
    <h2 class="text-2xl font-bold text-indigo-700 mb-4">🛠️ Create New Form</h2>
    <form @submit.prevent="submitForm">
        <!-- HEADER SECTION -->
        <div class="mb-6 border-b pb-5">
            <h3 class="text-lg font-semibold text-indigo-700 mb-2">Header (Advanced)</h3>
            <div class="flex flex-col md:flex-row md:items-end gap-5">
                <div class="flex-1">
                    <label class="block text-gray-700 mb-1">Header Image</label>
                    <input type="file" @change="onHeaderImageChange($event)" accept="image/*" class="block w-full text-sm text-gray-500" />
                    <input type="text" x-model="form.header.image" placeholder="Or paste image URL directly" class="block w-full border rounded p-2 mt-2" />
                </div>
                <template x-if="form.header.image">
                    <img :src="form.header.image" alt="Header Preview" class="w-24 h-24 object-cover rounded-full border-2 border-indigo-200 shadow" />
                </template>
            </div>
            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 mb-1">Form Title</label>
                    <input type="text" x-model="form.header.title" class="w-full border rounded p-2" placeholder="Form Title" required />
                </div>
                <div>
                    <label class="block text-gray-700 mb-1">Subtitle / Branding</label>
                    <input type="text" x-model="form.header.subtitle" class="w-full border rounded p-2" placeholder="E.g. Powered by Your Company" />
                </div>
            </div>
            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center gap-3">
                    <label class="text-gray-700">Title Alignment</label>
                    <select x-model="form.header.title_align" class="border rounded p-1">
                        <option value="left">Left</option>
                        <option value="center">Center</option>
                        <option value="right">Right</option>
                    </select>
                </div>
                <div class="flex items-center gap-3">
                    <label class="text-gray-700">Theme Color</label>
                    <input type="color" x-model="form.header.theme_color" class="w-8 h-8 border rounded-full" />
                </div>
            </div>
            <div class="mt-3">
                <label class="block text-gray-700 mb-1">Company Logo URL (optional)</label>
                <input type="text" x-model="form.header.logo" class="w-full border rounded p-2" placeholder="Logo URL" />
            </div>
        </div>
        <!-- FOOTER SECTION -->
        <div class="mb-6 border-b pb-5">
            <h3 class="text-lg font-semibold text-indigo-700 mb-2 flex items-center gap-2">
                Footer
                <label class="ml-2 text-sm flex items-center gap-1">
                    <input type="checkbox" x-model="form.footer.show_footer" class="h-4 w-4" />
                    Show Footer
                </label>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 mb-1">Company Website</label>
                    <input type="text" x-model="form.footer.website" class="w-full border rounded p-2" placeholder="https://yourcompany.com" />
                </div>
                <div>
                    <label class="block text-gray-700 mb-1">Support Number(s)</label>
                    <template x-for="(number, idx) in form.footer.support_numbers" :key="idx">
                        <div class="flex items-center gap-2 mt-1">
                            <input type="text" x-model="form.footer.support_numbers[idx]" class="w-full border rounded p-2" placeholder="+1-800-234-5678" />
                            <button type="button" class="text-red-600" @click="form.footer.support_numbers.splice(idx,1)">🗑️</button>
                        </div>
                    </template>
                    <button type="button" @click="form.footer.support_numbers.push('')" class="mt-2 text-sm text-indigo-600">
                        ➕ Add Support Number
                    </button>
                </div>
                <div>
                    <label class="block text-gray-700 mb-1">Support Email(s)</label>
                    <template x-for="(email, idx) in form.footer.support_emails" :key="idx">
                        <div class="flex items-center gap-2 mt-1">
                            <input type="text" x-model="form.footer.support_emails[idx]" class="w-full border rounded p-2" placeholder="support@yourcompany.com" />
                            <button type="button" class="text-red-600" @click="form.footer.support_emails.splice(idx,1)">🗑️</button>
                        </div>
                    </template>
                    <button type="button" @click="form.footer.support_emails.push('')" class="mt-2 text-sm text-indigo-600">
                        ➕ Add Support Email
                    </button>
                </div>
                <div>
                    <label class="block text-gray-700 mb-1">Social Media Links</label>
                    <template x-for="(link, idx) in form.footer.social" :key="idx">
                        <div class="flex items-center gap-2 mt-1">
                            <input type="text" x-model="form.footer.social[idx]" class="w-full border rounded p-2" placeholder="https://twitter.com/yourcompany" />
                            <button type="button" class="text-red-600" @click="form.footer.social.splice(idx,1)">🗑️</button>
                        </div>
                    </template>
                    <button type="button" @click="form.footer.social.push('')" class="mt-2 text-sm text-indigo-600">
                        ➕ Add Social Link
                    </button>
                </div>
                <div class="col-span-2">
                    <label class="block text-gray-700 mb-1">Extra Info / Notes</label>
                    <textarea x-model="form.footer.extra" class="w-full border rounded p-2"></textarea>
                </div>
            </div>
        </div>
        <!-- MAIN FIELD BUILDER -->
        <div class="space-y-4">
            <template x-for="(field, index) in form.fields" :key="index">
                <div class="p-4 bg-gray-50 border rounded-lg mt-1">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-semibold text-gray-700" x-text="'Field ' + (index + 1)"></h4>
                        <button type="button" class="text-red-500 hover:text-red-700 font-semibold" @click="removeField(index)">❌ Remove</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600">Label</label>
                            <input type="text" x-model="field.label"
                                @input="field.name = generateSlug(field.label)"
                                class="w-full border rounded p-2" required>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600">Name (auto-generated)</label>
                            <input type="text" x-model="field.name" class="w-full border rounded p-2 bg-gray-100" readonly>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600">Type</label>
                            <select x-model="field.type" @change="onTypeChange(index)" class="w-full border rounded p-2">
                                <option value="short_answer">Short Answer</option>
                                <option value="paragraph">Paragraph</option>
                                <option value="email">Email</option>
                                <option value="mobile">Mobile Number</option>
                                <option value="age">Age (Number)</option>
                                <option value="single_choice">Single Choice</option>
                                <option value="multiple_choice">Multiple Choice</option>
                                <option value="location">Location</option>
                                <option value="file">File Upload</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600">Validation Rules</label>
                            <input type="text" x-model="field.rules"
                                   @input="onRulesEdit(index)"
                                   class="w-full border rounded p-2"
                                   placeholder="e.g. required|string|max:100">
                            <div class="mt-2 flex items-center">
                                <input type="checkbox" :id="'required-' + index"
                                    @change="toggleRequiredRule(index, $event.target.checked)"
                                    class="h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                    :checked="field.rules.split('|').includes('required')">
                                <label :for="'required-' + index" class="ml-2 text-sm text-gray-800">Required</label>
                            </div>
                        </div>
                    </div>
                    <!-- Options for choice fields -->
                    <template x-if="field.type === 'single_choice' || field.type === 'multiple_choice'">
                        <div class="mt-2">
                            <label class="block text-gray-700 mb-1">Options</label>
                            <template x-for="(option, optIndex) in field.options" :key="optIndex">
                                <div class="flex gap-2 items-center mt-1">
                                    <input type="text" x-model="field.options[optIndex]" class="w-full border rounded p-1" placeholder="Option text">
                                    <button type="button" @click="removeOption(index, optIndex)" class="text-red-500 hover:text-red-700">🗑️</button>
                                </div>
                            </template>
                            <button type="button" class="mt-2 text-sm text-indigo-600 font-semibold" @click="addOption(index)">+ Add Option</button>
                        </div>
                    </template>
                </div>
            </template>
        </div>
        <div class="my-6 flex justify-between">
            <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition"
                @click="addField()">➕ Add Field</button>
            <button type="submit" :disabled="isSubmitting" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition disabled:bg-gray-400">
                <span x-show="!isSubmitting">✅ Save Form</span>
                <span x-show="isSubmitting">Saving...</span>
            </button>
        </div>
    </form>
</div>

<script>
function formBuilder(existingForm = null) {
    return {
        isSubmitting: false,
        form: existingForm || {
            title: 'My New Form',
            description: '',
            header: {
                image: '', title: '', subtitle: '', title_align: 'center', theme_color: '#6366f1', logo: ''
            },
            footer: {
                show_footer: true, website: '', support_numbers: [], support_emails: [], social: [], extra: ''
            },
            fields: [],
        },
        init() {
            // For editing, repopulate missing options string
            this.form.fields.forEach(field => {
                if ((field.type === 'single_choice' || field.type === 'multiple_choice') &&
                    field.options && !Array.isArray(field.options)) {
                    field.options = [];
                }
            });
            if (this.form.fields.length === 0) { this.addField(); }
        },
        addField() {
            this.form.fields.push({
                label: '', name: '', type: 'short_answer', rules: 'required|string|max:255', options: [],
                _customRules: false
            });
        },
        removeField(index) { this.form.fields.splice(index, 1); },
        addOption(fieldIndex) {
            if (!this.form.fields[fieldIndex].options) { this.form.fields[fieldIndex].options = []; }
            this.form.fields[fieldIndex].options.push('');
        },
        removeOption(fieldIndex, optionIndex) {
            this.form.fields[fieldIndex].options.splice(optionIndex, 1);
        },
        generateSlug(text) {
            return text
                ? text.toString().toLowerCase().replace(/\s+/g, '_').replace(/[^\w-]+/g, '').replace(/__+/g, '_').replace(/^-+/, '').replace(/-+$/, '')
                : '';
        },
        onTypeChange(index) {
            const field = this.form.fields[index];
            if (field._customRules) return;
            field.rules = this.getDefaultRules(field.type);
        },
        onRulesEdit(index) {
            this.form.fields[index]._customRules = true;
        },
        getDefaultRules(type) {
            switch(type) {
                case 'email':           return 'required|email|max:255';
                case 'mobile':          return 'required|string|min:7|max:16';
                case 'age':             return 'required|numeric|min:1|max:120';
                case 'paragraph':       return 'required|string|max:1000';
                case 'short_answer':    return 'required|string|max:255';
                case 'single_choice':   return 'required|string|max:255';
                case 'multiple_choice': return 'required|array';
                case 'location':        return 'required|string|max:255';
                case 'file':            return 'nullable|file|max:10240';
                default:                return 'required|string';
            }
        },
        toggleRequiredRule(index, isChecked) {
            const field = this.form.fields[index];
            let rulesArr = field.rules.split('|').map(r => r.trim()).filter(r => r);
            rulesArr = rulesArr.filter(rule => rule !== 'required');
            if (isChecked) rulesArr.unshift('required');
            field.rules = rulesArr.join('|');
            field._customRules = true;
        },
        submitForm() {
            this.isSubmitting = true;
            // Remove builder-only props
            this.form.fields = this.form.fields.map(field => {
                let f = { ...field };
                delete f._customRules;
                return f;
            });
            // Submit via POST/JSON
            fetch("<?php echo e(route('forms.store')); ?>", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.form)
            })
            .then(res => {
                if (!res.ok) { return res.json().then(err => { throw err; }); }
                return res.json();
            })
            .then(data => {
                if (data.id) {
                    window.location.href = "<?php echo e(url('forms')); ?>/" + data.id;
                }
            })
            .catch(error => {
                console.error('Submission failed:', error);
                alert('There was an error saving the form. Please check the console for details.');
                this.isSubmitting = false;
            });
        },
        onHeaderImageChange(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => this.form.header.image = e.target.result;
                reader.readAsDataURL(input.files[0]);
            }
        }
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\formnew\formApp\resources\views/forms/builder.blade.php ENDPATH**/ ?>