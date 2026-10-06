<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { ref } from 'vue';
import tasks from '@/routes/tasks';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import type { Task } from '@/types';

defineProps<{
    task: Task;
}>();

const open = ref(false);
</script>

<template>
    <Dialog v-model:open="open">
        <DialogTrigger as-child>
            <Button variant="destructive" size="sm" :aria-label="`Delete ${task.name}`">Delete</Button>
        </DialogTrigger>
        <DialogContent>
            <Form :action="tasks.destroy(task.id)" reset-on-success
                :options="{ only: ['tasks', 'flash'], preserveState: false, preserveScroll: true }" class="space-y-6"
                v-slot="{ errors, processing, reset, clearErrors }" @success="open = false">
                <DialogHeader class="space-y-3">
                    <DialogTitle>Are you sure you want to delete this task?</DialogTitle>
                    <DialogDescription>
                        Once “{{ task.name }}” is deleted, it will be permanently removed.
                        This action cannot be undone.
                    </DialogDescription>
                </DialogHeader>

                <InputError v-for="(error, field) in errors" :key="field" :message="error" />

                <DialogFooter class="gap-2">
                    <DialogClose as-child>
                        <Button type="button" variant="secondary" :disabled="processing"
                            @click="() => { clearErrors(); reset(); }">
                            Cancel
                        </Button>
                    </DialogClose>
                    <Button type="submit" variant="destructive" :disabled="processing">
                        Delete
                    </Button>
                </DialogFooter>
            </Form>
        </DialogContent>
    </Dialog>
</template>
