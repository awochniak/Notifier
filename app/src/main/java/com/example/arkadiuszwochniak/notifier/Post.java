package com.example.arkadiuszwochniak.notifier;

public class Post {
    private String title;
    private Boolean status;

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public Boolean getStatus() {
        return status;
    }

    public void setStatus(Boolean status) {
        this.status = status;
    }

    public Post(String title, Boolean status) {
        this.title = title;
        this.status = status;
    }

    public Post(){

    }
}
