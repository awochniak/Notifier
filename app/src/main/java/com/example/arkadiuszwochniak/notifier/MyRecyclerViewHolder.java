package com.example.arkadiuszwochniak.notifier;

import android.media.Image;
import android.support.annotation.NonNull;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

public class MyRecyclerViewHolder extends RecyclerView.ViewHolder {
    TextView name, status;
    ImageView imageView;

    public MyRecyclerViewHolder(@NonNull View itemView) {
        super(itemView);
        name = itemView.findViewById(R.id.name);
        status = itemView.findViewById(R.id.control);
        imageView = itemView.findViewById(R.id.imageView);
        imageView.setImageResource(android.R.drawable.presence_online);
    }
}