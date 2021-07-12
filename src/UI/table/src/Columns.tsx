const columns = (translate: ((a: string) => string)) : any => {
    return [
        {
            field: "version",
            title: "Version",
            defaultSort: "desc"
        },
        {
            field: "user_id",
            title: "User"
        },
        {
            field: "created_at",
            title: "Created At",
        }
    ];
};
export default columns;