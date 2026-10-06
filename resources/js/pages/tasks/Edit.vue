<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Drawer, DrawerContent, DrawerDescription, DrawerFooter, DrawerHeader, DrawerTitle, DrawerTrigger } from '@/components/ui/drawer';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import tasks from '@/routes/tasks';
import type { Task } from '@/types';

defineProps<{
    task: Task;
}>();

const open = ref(false);
</script>

<template>
    <Drawer v-model:open="open" swipe-direction="right">
        <DrawerTrigger as-child>
            <Button variant="outline" size="sm" :aria-label="`Edit ${task.name}`">Edit</Button>
        </DrawerTrigger>
        <DrawerContent>
            <DrawerHeader>
                <DrawerTitle>Edit task</DrawerTitle>
                <DrawerDescription>Update the name of this task.</DrawerDescription>
            </DrawerHeader>
            <Form :action="tasks.update(task.id)" v-slot="{ errors, processing }" class="flex min-h-0 flex-1 flex-col"
                :options="{ only: ['tasks', 'flash'], preserveScroll: true }" @success="open = false">
                <div class="grid gap-2 overflow-y-auto px-4 py-2">
                    <Label :for="`edit-task-name-${task.id}`">Name</Label>
                    <Input :id="`edit-task-name-${task.id}`" name="name" :default-value="task.name" required
                        maxlength="255" :disabled="processing" :aria-invalid="!!errors.name" />
                    <InputError :message="errors.name" />
                </div>
                <DrawerFooter>
                    <Button type="submit" :disabled="processing">Update</Button>
                    <Button type="button" variant="secondary" :disabled="processing"
                        @click="open = false">Cancel</Button>
                </DrawerFooter>
            </Form>
        </DrawerContent>
    </Drawer>
</template>
