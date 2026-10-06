export interface Project {
    id: number;
    name: string;
    created_at: string;
    updated_at: string;
    tasks?: Task[];
    [key: string]: unknown;
}

export interface Task {
    id: number;
    name: string;
    priority: number;
    created_at: string;
    updated_at: string;
    project?: Project;
    [key: string]: unknown;
}
